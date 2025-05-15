<?php

namespace App\Services\Pharmacy;

use App\Models\Prescription;
use App\Models\PrescriptionDelivery;
use App\Models\Medicine;
use App\Models\PharmacySale;
use App\Models\PharmacySaleItem;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PrescriptionService
{
    /**
     * Get prescriptions for a pharmacy with optional filters
     * 
     * @param int $pharmacyId
     * @param string|null $search
     * @param string|null $status
     * @param string|null $dateFilter
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getPrescriptions($pharmacyId, $search = null, $status = null, $dateFilter = null)
    {
        $query = Prescription::where('pharmacy_id', $pharmacyId)
            ->with(['patient', 'doctor.user', 'items.medicine']);
            
        // Apply search filter
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->whereHas('patient', function($sq) use ($search) {
                    $sq->whereHas('user', function($ssq) use ($search) {
                        $ssq->where('name', 'like', "%{$search}%");
                    });
                })
                ->orWhereHas('doctor', function($sq) use ($search) {
                    $sq->whereHas('user', function($ssq) use ($search) {
                        $ssq->where('name', 'like', "%{$search}%");
                    });
                });
            });
        }
        
        // Apply status filter
        if ($status) {
            $query->where('status', $status);
        }
        
        // Apply date filter
        if ($dateFilter) {
            $dates = $this->parseDateFilter($dateFilter);
            $query->whereBetween('created_at', [$dates['start'], $dates['end']]);
        }
        
        return $query->orderBy('created_at', 'desc')->paginate(15);
    }
    
    /**
     * Get a specific prescription
     * 
     * @param int $pharmacyId
     * @param int $prescriptionId
     * @return Prescription
     */
    public function getPrescription($pharmacyId, $prescriptionId)
    {
        return Prescription::where('pharmacy_id', $pharmacyId)
            ->where('id', $prescriptionId)
            ->with(['patient.user', 'doctor.user', 'items.medicine', 'delivery'])
            ->firstOrFail();
    }
    
    /**
     * Update prescription status
     * 
     * @param int $pharmacyId
     * @param int $prescriptionId
     * @param string $status
     * @param string|null $deliveryType
     * @return Prescription
     */
    public function updatePrescriptionStatus($pharmacyId, $prescriptionId, $status, $deliveryType = null)
    {
        DB::beginTransaction();
        
        try {
            $prescription = Prescription::where('pharmacy_id', $pharmacyId)
                ->where('id', $prescriptionId)
                ->firstOrFail();
                
            $prescription->status = $status;
            $prescription->save();
            
            // If status is accepted and delivery type is provided, create delivery record
            if ($status === 'accepted' && $deliveryType) {
                PrescriptionDelivery::create([
                    'prescription_id' => $prescriptionId,
                    'delivery_type' => $deliveryType,
                    'status' => 'pending',
                    'created_at' => Carbon::now()
                ]);
            }
            
            DB::commit();
            
            return $prescription->load(['patient.user', 'doctor.user', 'items.medicine', 'delivery']);
            
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
    
    /**
     * Process a prescription (fulfill it)
     * 
     * @param int $pharmacyId
     * @param int $prescriptionId
     * @return array
     */
    public function processPrescription($pharmacyId, $prescriptionId)
    {
        DB::beginTransaction();
        
        try {
            $prescription = Prescription::where('pharmacy_id', $pharmacyId)
                ->where('id', $prescriptionId)
                ->with(['patient', 'items.medicine'])
                ->firstOrFail();
                
            // Check if prescription is already processed
            if ($prescription->status === 'completed') {
                throw new \Exception('Prescription already processed');
            }
            
            // Check if all medicines are in stock
            foreach ($prescription->items as $item) {
                $medicine = Medicine::find($item->medicine_id);
                
                if ($medicine->stock_quantity < $item->quantity) {
                    throw new \Exception("Insufficient stock for {$medicine->name}");
                }
            }
            
            // Create sale
            $totalAmount = 0;
            foreach ($prescription->items as $item) {
                $medicine = Medicine::find($item->medicine_id);
                $totalAmount += $medicine->price * $item->quantity;
            }
            
            $sale = PharmacySale::create([
                'pharmacy_id' => $pharmacyId,
                'patient_id' => $prescription->patient_id,
                'total_amount' => $totalAmount,
                'credit_awarded' => floor($totalAmount / 100), // 1 point per 100 units of currency
                'sale_date' => Carbon::now(),
                'processed_by' => auth()->id()
            ]);
            
            // Create sale items and update medicine stock
            foreach ($prescription->items as $item) {
                $medicine = Medicine::find($item->medicine_id);
                
                PharmacySaleItem::create([
                    'sale_id' => $sale->id,
                    'medicine_id' => $item->medicine_id,
                    'quantity' => $item->quantity,
                    'price' => $medicine->price,
                    'subtotal' => $medicine->price * $item->quantity
                ]);
                
                // Update medicine stock
                $medicine->stock_quantity -= $item->quantity;
                $medicine->save();
            }
            
            // Update prescription status
            $prescription->status = 'completed';
            $prescription->save();
            
            // Update delivery status if applicable
            if ($prescription->delivery) {
                $prescription->delivery->status = 'processing';
                $prescription->delivery->save();
            }
            
            DB::commit();
            
            return [
                'prescription' => $prescription->load(['patient.user', 'doctor.user', 'items.medicine', 'delivery']),
                'sale' => $sale->load('items.medicine')
            ];
            
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
    
    /**
     * Parse date filter string to start and end dates
     * 
     * @param string $dateFilter
     * @return array
     */
    private function parseDateFilter($dateFilter)
    {
        switch ($dateFilter) {
            case 'today':
                return [
                    'start' => Carbon::now()->startOfDay(),
                    'end' => Carbon::now()->endOfDay()
                ];
            case 'yesterday':
                return [
                    'start' => Carbon::yesterday()->startOfDay(),
                    'end' => Carbon::yesterday()->endOfDay()
                ];
            case 'this_week':
                return [
                    'start' => Carbon::now()->startOfWeek(),
                    'end' => Carbon::now()->endOfWeek()
                ];
            case 'last_week':
                return [
                    'start' => Carbon::now()->subWeek()->startOfWeek(),
                    'end' => Carbon::now()->subWeek()->endOfWeek()
                ];
            case 'this_month':
                return [
                    'start' => Carbon::now()->startOfMonth(),
                    'end' => Carbon::now()->endOfMonth()
                ];
            case 'last_month':
                return [
                    'start' => Carbon::now()->subMonth()->startOfMonth(),
                    'end' => Carbon::now()->subMonth()->endOfMonth()
                ];
            default:
                // Custom date range in format 'YYYY-MM-DD,YYYY-MM-DD'
                if (strpos($dateFilter, ',') !== false) {
                    list($start, $end) = explode(',', $dateFilter);
                    return [
                        'start' => Carbon::parse($start)->startOfDay(),
                        'end' => Carbon::parse($end)->endOfDay()
                    ];
                }
                
                // Default to this month
                return [
                    'start' => Carbon::now()->startOfMonth(),
                    'end' => Carbon::now()->endOfMonth()
                ];
        }
    }
}

<?php

namespace App\Services\Pharmacy;

use App\Models\Bill;
use App\Models\PharmacySale;
use App\Models\PharmacySaleItem;
use App\Models\Patient;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class BillingService
{
    /**
     * Get sales for a pharmacy with optional filters
     * 
     * @param int $pharmacyId
     * @param string|null $search
     * @param string|null $dateFilter
     * @param string|null $paymentMethod
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getSales($pharmacyId, $search = null, $dateFilter = null, $paymentMethod = null)
    {
        $query = PharmacySale::where('pharmacy_id', $pharmacyId)
            ->with(['patient', 'items.medicine']);
            
        // Apply search filter
        if ($search) {
            $query->whereHas('patient', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }
        
        // Apply date filter
        if ($dateFilter) {
            $dates = $this->parseDateFilter($dateFilter);
            $query->whereBetween('sale_date', [$dates['start'], $dates['end']]);
        }
        
        // Apply payment method filter
        if ($paymentMethod) {
            $query->whereHas('bill', function($q) use ($paymentMethod) {
                $q->where('payment_method', $paymentMethod);
            });
        }
        
        return $query->orderBy('sale_date', 'desc')->paginate(15);
    }
    
    /**
     * Get bills for a pharmacy with optional filters
     * 
     * @param int $pharmacyId
     * @param string|null $search
     * @param string|null $dateFilter
     * @param string|null $paymentMethod
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getBills($pharmacyId, $search = null, $dateFilter = null, $paymentMethod = null)
    {
        $query = Bill::where('pharmacy_id', $pharmacyId)
            ->with(['patient', 'sale.items.medicine']);
            
        // Apply search filter
        if ($search) {
            $query->whereHas('patient', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }
        
        // Apply date filter
        if ($dateFilter) {
            $dates = $this->parseDateFilter($dateFilter);
            $query->whereBetween('created_at', [$dates['start'], $dates['end']]);
        }
        
        // Apply payment method filter
        if ($paymentMethod) {
            $query->where('payment_method', $paymentMethod);
        }
        
        return $query->orderBy('created_at', 'desc')->paginate(15);
    }
    
    /**
     * Get billing statistics for a pharmacy
     * 
     * @param int $pharmacyId
     * @param string|null $dateFilter
     * @return array
     */
    public function getBillingStats($pharmacyId, $dateFilter = null)
    {
        // Set date range
        if ($dateFilter) {
            $dates = $this->parseDateFilter($dateFilter);
            $startDate = $dates['start'];
            $endDate = $dates['end'];
        } else {
            $startDate = Carbon::now()->startOfMonth();
            $endDate = Carbon::now();
        }
        
        // Get total sales amount
        $totalSales = PharmacySale::where('pharmacy_id', $pharmacyId)
            ->whereBetween('sale_date', [$startDate, $endDate])
            ->sum('total_amount');
            
        // Get sales count
        $salesCount = PharmacySale::where('pharmacy_id', $pharmacyId)
            ->whereBetween('sale_date', [$startDate, $endDate])
            ->count();
            
        // Get payment method distribution
        $paymentMethods = Bill::where('pharmacy_id', $pharmacyId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select('payment_method', DB::raw('COUNT(*) as count'))
            ->groupBy('payment_method')
            ->get()
            ->pluck('count', 'payment_method')
            ->toArray();
            
        // Get top selling medicines
        $topMedicines = PharmacySaleItem::whereHas('sale', function($q) use ($pharmacyId, $startDate, $endDate) {
                $q->where('pharmacy_id', $pharmacyId)
                  ->whereBetween('sale_date', [$startDate, $endDate]);
            })
            ->select('medicine_id', DB::raw('SUM(quantity) as total_quantity'))
            ->with('medicine:id,name')
            ->groupBy('medicine_id')
            ->orderBy('total_quantity', 'desc')
            ->limit(5)
            ->get()
            ->map(function($item) {
                return [
                    'name' => $item->medicine->name,
                    'quantity' => $item->total_quantity
                ];
            });
            
        return [
            'total_sales' => $totalSales,
            'sales_count' => $salesCount,
            'payment_methods' => $paymentMethods,
            'top_medicines' => $topMedicines,
            'period' => [
                'start' => $startDate->toDateString(),
                'end' => $endDate->toDateString()
            ]
        ];
    }
    
    /**
     * Create a new bill
     * 
     * @param int $pharmacyId
     * @param array $data
     * @return Bill
     */
    public function createBill($pharmacyId, array $data)
    {
        DB::beginTransaction();
        
        try {
            // Create sale
            $sale = PharmacySale::create([
                'pharmacy_id' => $pharmacyId,
                'patient_id' => $data['patient_id'],
                'total_amount' => $data['total_amount'],
                'credit_awarded' => $data['credit_awarded'] ?? 0,
                'sale_date' => Carbon::now(),
                'processed_by' => auth()->id()
            ]);
            
            // Create sale items
            foreach ($data['items'] as $item) {
                PharmacySaleItem::create([
                    'sale_id' => $sale->id,
                    'medicine_id' => $item['medicine_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['quantity'] * $item['price']
                ]);
                
                // Update medicine stock
                $medicine = \App\Models\Medicine::find($item['medicine_id']);
                $medicine->stock_quantity -= $item['quantity'];
                $medicine->save();
            }
            
            // Create bill
            $bill = Bill::create([
                'pharmacy_id' => $pharmacyId,
                'patient_id' => $data['patient_id'],
                'sale_id' => $sale->id,
                'payment_method' => $data['payment_method'],
                'total_price' => $data['total_amount'],
                'created_at' => Carbon::now()
            ]);
            
            // Update patient credit points if applicable
            if (isset($data['credit_awarded']) && $data['credit_awarded'] > 0) {
                $patient = Patient::find($data['patient_id']);
                $patient->credit_points += $data['credit_awarded'];
                $patient->save();
            }
            
            DB::commit();
            return $bill->load(['patient', 'sale.items.medicine']);
            
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
            case 'this_year':
                return [
                    'start' => Carbon::now()->startOfYear(),
                    'end' => Carbon::now()->endOfYear()
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

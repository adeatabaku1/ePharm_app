<?php

namespace App\Services\Pharmacy;

use App\Models\PrescriptionDelivery;
use App\Models\Prescription;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class OrderService
{
    /**
     * Get orders for a pharmacy with optional filters
     * 
     * @param int $pharmacyId
     * @param string|null $search
     * @param string|null $status
     * @param string|null $dateFilter
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getOrders($pharmacyId, $search = null, $status = null, $dateFilter = null)
    {
        // Orders are essentially prescriptions with delivery information
        $query = Prescription::where('pharmacy_id', $pharmacyId)
            ->whereIn('status', ['accepted', 'completed'])
            ->with(['patient.user', 'doctor.user', 'items.medicine', 'delivery']);
            
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
            if ($status === 'pending_delivery') {
                $query->whereHas('delivery', function($q) {
                    $q->where('status', 'pending');
                });
            } elseif ($status === 'in_transit') {
                $query->whereHas('delivery', function($q) {
                    $q->where('status', 'in_transit');
                });
            } elseif ($status === 'delivered') {
                $query->whereHas('delivery', function($q) {
                    $q->where('status', 'delivered');
                });
            } elseif ($status === 'pickup') {
                $query->whereHas('delivery', function($q) {
                    $q->where('delivery_type', 'pickup');
                });
            }
        }
        
        // Apply date filter
        if ($dateFilter) {
            $dates = $this->parseDateFilter($dateFilter);
            $query->whereBetween('created_at', [$dates['start'], $dates['end']]);
        }
        
        return $query->orderBy('created_at', 'desc')->paginate(15);
    }
    
    /**
     * Get a specific order
     * 
     * @param int $pharmacyId
     * @param int $orderId
     * @return array
     */
    public function getOrder($pharmacyId, $orderId)
    {
        $prescription = Prescription::where('pharmacy_id', $pharmacyId)
            ->where('id', $orderId)
            ->whereIn('status', ['accepted', 'completed'])
            ->with(['patient.user', 'doctor.user', 'items.medicine', 'delivery'])
            ->firstOrFail();
            
        // Get total price
        $totalPrice = 0;
        foreach ($prescription->items as $item) {
            $totalPrice += $item->medicine->price * $item->quantity;
        }
        
        return [
            'order' => $prescription,
            'total_price' => $totalPrice
        ];
    }
    
    /**
     * Update order status
     * 
     * @param int $pharmacyId
     * @param int $orderId
     * @param string $status
     * @return array
     */
    public function updateOrderStatus($pharmacyId, $orderId, $status)
    {
        $prescription = Prescription::where('pharmacy_id', $pharmacyId)
            ->where('id', $orderId)
            ->whereIn('status', ['accepted', 'completed'])
            ->with(['delivery'])
            ->firstOrFail();
            
        if (!$prescription->delivery) {
            throw new \Exception('Order does not have delivery information');
        }
        
        // Update delivery status
        $delivery = $prescription->delivery;
        $delivery->status = $status;
        
        if ($status === 'delivered') {
            $delivery->delivered_at = Carbon::now();
        }
        
        $delivery->save();
        
        // If delivery is completed, mark prescription as completed
        if ($status === 'delivered' && $prescription->status !== 'completed') {
            $prescription->status = 'completed';
            $prescription->save();
        }
        
        return [
            'order' => $prescription->load(['patient.user', 'doctor.user', 'items.medicine', 'delivery'])
        ];
    }
    
    /**
     * Get order statistics
     * 
     * @param int $pharmacyId
     * @param string|null $dateFilter
     * @return array
     */
    public function getOrderStats($pharmacyId, $dateFilter = null)
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
        
        // Get total orders
        $totalOrders = Prescription::where('pharmacy_id', $pharmacyId)
            ->whereIn('status', ['accepted', 'completed'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();
            
        // Get orders by status
        $ordersByStatus = PrescriptionDelivery::whereHas('prescription', function($q) use ($pharmacyId, $startDate, $endDate) {
                $q->where('pharmacy_id', $pharmacyId)
                  ->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status')
            ->toArray();
            
        // Get orders by delivery type
        $ordersByType = PrescriptionDelivery::whereHas('prescription', function($q) use ($pharmacyId, $startDate, $endDate) {
                $q->where('pharmacy_id', $pharmacyId)
                  ->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->select('delivery_type', DB::raw('COUNT(*) as count'))
            ->groupBy('delivery_type')
            ->get()
            ->pluck('count', 'delivery_type')
            ->toArray();
            
        // Get daily orders for chart
        $dailyOrders = Prescription::where('pharmacy_id', $pharmacyId)
            ->whereIn('status', ['accepted', 'completed'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('date')
            ->get()
            ->pluck('count', 'date')
            ->toArray();
            
        return [
            'total_orders' => $totalOrders,
            'orders_by_status' => $ordersByStatus,
            'orders_by_type' => $ordersByType,
            'daily_orders' => $dailyOrders,
            'period' => [
                'start' => $startDate->toDateString(),
                'end' => $endDate->toDateString()
            ]
        ];
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

<?php

namespace App\Services\Pharmacy;

use App\Models\Bill;
use App\Models\Medicine;
use App\Models\Pharmacy;
use App\Models\PharmacySale;
use App\Models\Prescription;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PharmacyService
{
    /**
     * Get dashboard statistics for a pharmacy
     * 
     * @param int $pharmacyId
     * @param string $period
     * @return array
     */
    public function getDashboardStats($pharmacyId, $period = 'week')
    {
        $pharmacy = Pharmacy::findOrFail($pharmacyId);
        $now = Carbon::now();
        
        // Set date range based on period
        switch ($period) {
            case 'day':
                $startDate = $now->copy()->startOfDay();
                break;
            case 'month':
                $startDate = $now->copy()->startOfMonth();
                break;
            case 'year':
                $startDate = $now->copy()->startOfYear();
                break;
            case 'week':
            default:
                $startDate = $now->copy()->startOfWeek();
                break;
        }
        
        // Get sales data
        $totalSales = PharmacySale::where('pharmacy_id', $pharmacyId)
            ->where('sale_date', '>=', $startDate)
            ->sum('total_amount');
            
        $salesCount = PharmacySale::where('pharmacy_id', $pharmacyId)
            ->where('sale_date', '>=', $startDate)
            ->count();
            
        // Get prescription data
        $pendingPrescriptions = Prescription::where('pharmacy_id', $pharmacyId)
            ->where('status', 'pending')
            ->count();
            
        // Get low stock medicines
        $lowStockCount = Medicine::where('tenant_id', $pharmacy->tenant_id)
            ->where('stock_quantity', '<', 10)
            ->count();
            
        // Get daily sales for chart
        $dailySales = PharmacySale::where('pharmacy_id', $pharmacyId)
            ->where('sale_date', '>=', $startDate)
            ->select(
                DB::raw('DATE(sale_date) as date'),
                DB::raw('SUM(total_amount) as total')
            )
            ->groupBy('date')
            ->get()
            ->pluck('total', 'date')
            ->toArray();
            
        return [
            'total_sales' => $totalSales,
            'sales_count' => $salesCount,
            'pending_prescriptions' => $pendingPrescriptions,
            'low_stock_count' => $lowStockCount,
            'daily_sales' => $dailySales,
            'period' => $period
        ];
    }
    
    /**
     * Get pharmacy settings
     * 
     * @param int $pharmacyId
     * @return Pharmacy
     */
    public function getSettings($pharmacyId)
    {
        return Pharmacy::findOrFail($pharmacyId);
    }
    
    /**
     * Update pharmacy settings
     * 
     * @param int $pharmacyId
     * @param array $data
     * @return Pharmacy
     */
    public function updateSettings($pharmacyId, array $data)
    {
        $pharmacy = Pharmacy::findOrFail($pharmacyId);
        $pharmacy->update($data);
        
        return $pharmacy;
    }
}

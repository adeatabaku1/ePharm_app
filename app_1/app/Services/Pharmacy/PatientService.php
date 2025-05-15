<?php

namespace App\Services\Pharmacy;

use App\Models\Patient;
use App\Models\PharmacyCredit;
use App\Models\PharmacySale;
use Illuminate\Support\Facades\DB;

class PatientService
{
    /**
     * Get patients associated with a pharmacy with optional filters
     * 
     * @param int $pharmacyId
     * @param string|null $search
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getPatients($pharmacyId, $search = null)
    {
        // Get patients who have made purchases at this pharmacy
        $query = Patient::whereHas('sales', function($q) use ($pharmacyId) {
                $q->where('pharmacy_id', $pharmacyId);
            })
            ->with(['user', 'sales' => function($q) use ($pharmacyId) {
                $q->where('pharmacy_id', $pharmacyId)
                  ->select('id', 'patient_id', 'total_amount', 'sale_date')
                  ->orderBy('sale_date', 'desc')
                  ->limit(1);
            }]);
            
        // Apply search filter
        if ($search) {
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        return $query->orderBy('id')->paginate(15);
    }
    
    /**
     * Get a specific patient
     * 
     * @param int $pharmacyId
     * @param int $patientId
     * @return array
     */
    public function getPatient($pharmacyId, $patientId)
    {
        $patient = Patient::with(['user'])
            ->findOrFail($patientId);
            
        // Get purchase statistics
        $purchaseStats = PharmacySale::where('patient_id', $patientId)
            ->where('pharmacy_id', $pharmacyId)
            ->select(
                DB::raw('COUNT(*) as total_purchases'),
                DB::raw('SUM(total_amount) as total_spent'),
                DB::raw('AVG(total_amount) as average_purchase'),
                DB::raw('MAX(sale_date) as last_purchase_date')
            )
            ->first();
            
        // Get credit points
        $creditPoints = PharmacyCredit::where('patient_id', $patientId)
            ->where('pharmacy_id', $pharmacyId)
            ->sum('points');
            
        return [
            'patient' => $patient,
            'stats' => $purchaseStats,
            'credit_points' => $creditPoints
        ];
    }
    
    /**
     * Get patient purchase history
     * 
     * @param int $pharmacyId
     * @param int $patientId
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getPatientPurchaseHistory($pharmacyId, $patientId)
    {
        return PharmacySale::where('patient_id', $patientId)
            ->where('pharmacy_id', $pharmacyId)
            ->with(['items.medicine'])
            ->orderBy('sale_date', 'desc')
            ->paginate(10);
    }
    
    /**
     * Get patient credit points
     * 
     * @param int $pharmacyId
     * @param int $patientId
     * @return array
     */
    public function getPatientCreditPoints($pharmacyId, $patientId)
    {
        // Get current credit points
        $currentPoints = PharmacyCredit::where('patient_id', $patientId)
            ->where('pharmacy_id', $pharmacyId)
            ->sum('points');
            
        // Get credit history
        $creditHistory = PharmacyCredit::where('patient_id', $patientId)
            ->where('pharmacy_id', $pharmacyId)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return [
            'current_points' => $currentPoints,
            'history' => $creditHistory
        ];
    }
}

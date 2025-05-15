<?php

namespace App\Services\Pharmacy;

use App\Models\Doctor;
use App\Models\Pharmacy;
use App\Models\Prescription;
use Illuminate\Support\Facades\DB;

class DoctorService
{
    /**
     * Get doctors associated with a pharmacy with optional filters
     * 
     * @param int $pharmacyId
     * @param string|null $search
     * @param string|null $status
     * @param string|null $specialization
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getDoctors($pharmacyId, $search = null, $status = null, $specialization = null)
    {
        // Get doctors who have sent prescriptions to this pharmacy
        $query = Doctor::whereHas('prescriptions', function($q) use ($pharmacyId) {
                $q->where('pharmacy_id', $pharmacyId);
            })
            ->with(['user', 'prescriptions' => function($q) use ($pharmacyId) {
                $q->where('pharmacy_id', $pharmacyId)
                  ->select('id', 'doctor_id', 'created_at');
            }]);
            
        // Apply search filter
        if ($search) {
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }
        
        // Apply status filter
        if ($status) {
            $query->where('is_verified', $status === 'verified');
        }
        
        // Apply specialization filter
        if ($specialization) {
            $query->where('specialization', $specialization);
        }
        
        return $query->orderBy('id')->paginate(15);
    }
    
    /**
     * Get a specific doctor
     * 
     * @param int $pharmacyId
     * @param int $doctorId
     * @return array
     */
    public function getDoctor($pharmacyId, $doctorId)
    {
        $doctor = Doctor::with(['user', 'prescriptions' => function($q) use ($pharmacyId) {
                $q->where('pharmacy_id', $pharmacyId);
            }])
            ->findOrFail($doctorId);
            
        // Get prescription statistics
        $prescriptionStats = Prescription::where('doctor_id', $doctorId)
            ->where('pharmacy_id', $pharmacyId)
            ->select(
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(CASE WHEN status = "completed" THEN 1 ELSE 0 END) as completed'),
                DB::raw('SUM(CASE WHEN status = "pending" THEN 1 ELSE 0 END) as pending'),
                DB::raw('SUM(CASE WHEN status = "rejected" THEN 1 ELSE 0 END) as rejected')
            )
            ->first();
            
        // Get recent prescriptions
        $recentPrescriptions = Prescription::where('doctor_id', $doctorId)
            ->where('pharmacy_id', $pharmacyId)
            ->with(['patient:id,name', 'items.medicine:id,name'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
            
        return [
            'doctor' => $doctor,
            'stats' => $prescriptionStats,
            'recent_prescriptions' => $recentPrescriptions
        ];
    }
    
    /**
     * Get doctor specializations
     * 
     * @return array
     */
    public function getSpecializations()
    {
        return Doctor::select('specialization')
            ->distinct()
            ->whereNotNull('specialization')
            ->pluck('specialization')
            ->toArray();
    }
}

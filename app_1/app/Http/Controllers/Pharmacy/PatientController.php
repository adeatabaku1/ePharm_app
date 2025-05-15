<?php

namespace App\Http\Controllers\Pharmacy;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pharmacy\PatientFilterRequest;
use App\Services\Pharmacy\PatientService;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    protected $patientService;

    public function __construct(PatientService $patientService)
    {
        $this->patientService = $patientService;
    }

    /**
     * Get patients associated with a pharmacy
     * 
     * @param int $pharmacyId
     * @param PatientFilterRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPatients($pharmacyId, PatientFilterRequest $request)
    {
        $patients = $this->patientService->getPatients(
            $pharmacyId, 
            $request->search
        );
        
        return response()->json($patients);
    }

    /**
     * Get a specific patient
     * 
     * @param int $pharmacyId
     * @param int $patientId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPatient($pharmacyId, $patientId)
    {
        $patient = $this->patientService->getPatient($pharmacyId, $patientId);
        return response()->json($patient);
    }

    /**
     * Get patient purchase history
     * 
     * @param int $pharmacyId
     * @param int $patientId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPatientPurchaseHistory($pharmacyId, $patientId)
    {
        $history = $this->patientService->getPatientPurchaseHistory($pharmacyId, $patientId);
        return response()->json($history);
    }

    /**
     * Get patient credit points
     * 
     * @param int $pharmacyId
     * @param int $patientId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPatientCreditPoints($pharmacyId, $patientId)
    {
        $credits = $this->patientService->getPatientCreditPoints($pharmacyId, $patientId);
        return response()->json($credits);
    }
}

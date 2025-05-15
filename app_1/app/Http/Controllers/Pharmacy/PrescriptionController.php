<?php

namespace App\Http\Controllers\Pharmacy;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pharmacy\PrescriptionFilterRequest;
use App\Http\Requests\Pharmacy\PrescriptionStatusRequest;
use App\Services\Pharmacy\PrescriptionService;
use Illuminate\Http\Request;

class PrescriptionController extends Controller
{
    protected $prescriptionService;

    public function __construct(PrescriptionService $prescriptionService)
    {
        $this->prescriptionService = $prescriptionService;
    }

    /**
     * Get prescriptions for a pharmacy
     * 
     * @param int $pharmacyId
     * @param PrescriptionFilterRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPrescriptions($pharmacyId, PrescriptionFilterRequest $request)
    {
        $prescriptions = $this->prescriptionService->getPrescriptions(
            $pharmacyId, 
            $request->search, 
            $request->status,
            $request->date_filter
        );
        
        return response()->json($prescriptions);
    }

    /**
     * Get a specific prescription
     * 
     * @param int $pharmacyId
     * @param int $prescriptionId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPrescription($pharmacyId, $prescriptionId)
    {
        $prescription = $this->prescriptionService->getPrescription($pharmacyId, $prescriptionId);
        return response()->json($prescription);
    }

    /**
     * Update prescription status
     * 
     * @param int $pharmacyId
     * @param int $prescriptionId
     * @param PrescriptionStatusRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updatePrescriptionStatus($pharmacyId, $prescriptionId, PrescriptionStatusRequest $request)
    {
        $prescription = $this->prescriptionService->updatePrescriptionStatus(
            $pharmacyId, 
            $prescriptionId, 
            $request->status,
            $request->delivery_type
        );
        
        return response()->json($prescription);
    }

    /**
     * Process a prescription (fulfill it)
     * 
     * @param int $pharmacyId
     * @param int $prescriptionId
     * @return \Illuminate\Http\JsonResponse
     */
    public function processPrescription($pharmacyId, $prescriptionId)
    {
        $result = $this->prescriptionService->processPrescription($pharmacyId, $prescriptionId);
        return response()->json($result);
    }
}

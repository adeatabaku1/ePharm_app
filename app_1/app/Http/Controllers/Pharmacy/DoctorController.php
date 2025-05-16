<?php

namespace App\Http\Controllers\Pharmacy;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pharmacy\DoctorFilterRequest;
use App\Services\Pharmacy\DoctorService;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    protected $doctorService;

    public function __construct(DoctorService $doctorService)
    {
        $this->doctorService = $doctorService;
    }

    /**
     * Get doctors associated with a pharmacy
     * 
     * @param int $pharmacyId
     * @param DoctorFilterRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDoctors($pharmacyId, DoctorFilterRequest $request)
    {
        $doctors = $this->doctorService->getDoctors(
            $pharmacyId, 
            $request->search, 
            $request->status, 
            $request->specialization
        );
        
        return response()->json($doctors);
    }

    /**
     * Get a specific doctor
     * 
     * @param int $pharmacyId
     * @param int $doctorId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDoctor($pharmacyId, $doctorId)
    {
        $doctor = $this->doctorService->getDoctor($pharmacyId, $doctorId);
        return response()->json($doctor);
    }

    /**
     * Get doctor specializations
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSpecializations()
    {
        $specializations = $this->doctorService->getSpecializations();
        return response()->json($specializations);
    }
}

<?php

namespace App\Http\Controllers\Pharmacy;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pharmacy\MedicineFilterRequest;
use App\Http\Requests\Pharmacy\MedicineRequest;
use App\Services\Pharmacy\MedicineService;
use Illuminate\Http\Request;

class MedicineController extends Controller
{
    protected $medicineService;

    public function __construct(MedicineService $medicineService)
    {
        $this->medicineService = $medicineService;
    }

    /**
     * Get medicines for a pharmacy
     * 
     * @param int $pharmacyId
     * @param MedicineFilterRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMedicines($pharmacyId, MedicineFilterRequest $request)
    {
        $medicines = $this->medicineService->getMedicines(
            $pharmacyId, 
            $request->search, 
            $request->category, 
            $request->stock_level
        );
        
        return response()->json($medicines);
    }

    /**
     * Get a specific medicine
     * 
     * @param int $pharmacyId
     * @param int $medicineId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMedicine($pharmacyId, $medicineId)
    {
        $medicine = $this->medicineService->getMedicine($pharmacyId, $medicineId);
        return response()->json($medicine);
    }

    /**
     * Create a new medicine
     * 
     * @param int $pharmacyId
     * @param MedicineRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createMedicine($pharmacyId, MedicineRequest $request)
    {
        $medicine = $this->medicineService->createMedicine($pharmacyId, $request->validated());
        return response()->json($medicine, 201);
    }

    /**
     * Update a medicine
     * 
     * @param int $pharmacyId
     * @param int $medicineId
     * @param MedicineRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateMedicine($pharmacyId, $medicineId, MedicineRequest $request)
    {
        $medicine = $this->medicineService->updateMedicine($pharmacyId, $medicineId, $request->validated());
        return response()->json($medicine);
    }

    /**
     * Delete a medicine
     * 
     * @param int $pharmacyId
     * @param int $medicineId
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteMedicine($pharmacyId, $medicineId)
    {
        $this->medicineService->deleteMedicine($pharmacyId, $medicineId);
        return response()->json(null, 204);
    }

    /**
     * Get medicine categories
     * 
     * @param int $pharmacyId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMedicineCategories($pharmacyId)
    {
        $categories = $this->medicineService->getMedicineCategories($pharmacyId);
        return response()->json($categories);
    }
}

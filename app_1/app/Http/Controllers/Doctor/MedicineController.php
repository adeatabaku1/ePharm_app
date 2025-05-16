<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Doctor\StoreMedicineRequest;
use App\Http\Requests\Doctor\UpdateMedicineRequest;
use App\Http\Resources\Doctor\MedicineResource;
use App\Services\Doctor\MedicineService;
use Illuminate\Http\JsonResponse;

class MedicineController extends Controller
{
    protected MedicineService $medicineService;

    public function __construct(MedicineService $medicineService)
    {
        $this->medicineService = $medicineService;
    }

    public function index(): JsonResponse
    {
        $medicines = $this->medicineService->getAll();
        return response()->json([
            'data' => MedicineResource::collection($medicines)
        ]);
    }

    public function store(StoreMedicineRequest $request): JsonResponse
    {
        $medicine = $this->medicineService->create($request->validated());
        return response()->json(new MedicineResource($medicine), 201);
    }

    public function show($id): JsonResponse
    {
        $medicine = $this->medicineService->getById($id);
        return response()->json(new MedicineResource($medicine));
    }

    public function update(UpdateMedicineRequest $request, $id): JsonResponse
    {
        $medicine = $this->medicineService->update($id, $request->validated());
        return response()->json(new MedicineResource($medicine));
    }

    public function destroy($id): JsonResponse
    {
        $this->medicineService->delete($id);
        return response()->json(['message' => 'Medicine deleted successfully']);
    }
}

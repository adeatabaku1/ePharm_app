<?php

namespace App\Http\Controllers\Admin;

use App\Models\Medicine;
use App\Http\Requests\Admin\StoreMedicineRequest;
use App\Http\Requests\Admin\UpdateMedicineRequest;

class MedicineController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Medicine::paginate(15));
    }

    public function store(StoreMedicineRequest $request): JsonResponse
    {
        $medicine = Medicine::create($request->validated());
        return response()->json($medicine, 201);
    }

    public function show(Medicine $medicine): JsonResponse
    {
        return response()->json($medicine);
    }

    public function update(UpdateMedicineRequest $request, Medicine $medicine): JsonResponse
    {
        $medicine->update($request->validated());
        return response()->json($medicine);
    }

    public function destroy(Medicine $medicine): JsonResponse
    {
        $medicine->delete();
        return response()->json(null, 204);
    }
}

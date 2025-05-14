<?php

namespace App\Http\Controllers\Admin;

use App\Models\Pharmacy;
use App\Http\Requests\Admin\StorePharmacyRequest;
use App\Http\Requests\Admin\UpdatePharmacyRequest;

class PharmacyController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Pharmacy::paginate(15));
    }

    public function store(StorePharmacyRequest $request): JsonResponse
    {
        $pharmacy = Pharmacy::create($request->validated());
        return response()->json($pharmacy, 201);
    }

    public function show(Pharmacy $pharmacy): JsonResponse
    {
        return response()->json($pharmacy);
    }

    public function update(UpdatePharmacyRequest $request, Pharmacy $pharmacy): JsonResponse
    {
        $pharmacy->update($request->validated());
        return response()->json($pharmacy);
    }

    public function destroy(Pharmacy $pharmacy): JsonResponse
    {
        $pharmacy->delete();
        return response()->json(null, 204);
    }
}


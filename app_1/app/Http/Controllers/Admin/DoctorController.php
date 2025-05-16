<?php

namespace App\Http\Controllers\Admin;

use App\Models\Doctor;
use App\Http\Requests\Admin\StoreDoctorRequest;
use App\Http\Requests\Admin\UpdateDoctorRequest;

class DoctorController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Doctor::with('user')->paginate(15));
    }

    public function store(StoreDoctorRequest $request): JsonResponse
    {
        $doctor = Doctor::create($request->validated());
        return response()->json($doctor, 201);
    }

    public function show(Doctor $doctor): JsonResponse
    {
        return response()->json($doctor->load('user'));
    }

    public function update(UpdateDoctorRequest $request, Doctor $doctor): JsonResponse
    {
        $doctor->update($request->validated());
        return response()->json($doctor);
    }

    public function destroy(Doctor $doctor): JsonResponse
    {
        $doctor->delete();
        return response()->json(null, 204);
    }
}


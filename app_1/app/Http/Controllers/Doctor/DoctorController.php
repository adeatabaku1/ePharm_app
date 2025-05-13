<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Doctor\UpdateProfileRequest;
use App\Http\Resources\Doctor\DoctorResource;
use App\Services\Doctor\DoctorService;
use Illuminate\Http\JsonResponse;

class DoctorController extends Controller
{
    protected DoctorService $doctorService;

    public function __construct(DoctorService $doctorService)
    {
        $this->doctorService = $doctorService;
    }

    public function profile(): JsonResponse
    {
        $doctor = $this->doctorService->getProfile(auth()->id());

        return response()->json([
            'success' => true,
            'data' => new DoctorResource($doctor)
        ]);
    }

    public function update(UpdateProfileRequest $request): JsonResponse
    {
        $doctor = $this->doctorService->updateProfile(auth()->id(), $request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully.',
            'data' => new DoctorResource($doctor)
        ]);
    }
}

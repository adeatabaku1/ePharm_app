<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Doctor\StoreDoctorRequest;
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

    /**
     * @OA\Post(
     *     path="/api/doctors",
     *     summary="Create a new doctor",
     *     tags={"Doctor"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/StoreDoctorRequest")
     *     ),
     *     @OA\Response(response=201, description="Doctor created successfully."),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */


    public function store(StoreDoctorRequest $request): JsonResponse
    {
        $doctor = $this->doctorService->createDoctor($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Doctor created successfully.',
            'data' => new DoctorResource($doctor)
        ], 201);
    }
}

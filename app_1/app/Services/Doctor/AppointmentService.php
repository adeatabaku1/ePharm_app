<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Doctor\StoreAppointmentRequest;
use App\Http\Resources\Doctor\AppointmentResource;
use App\Services\Doctor\AppointmentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    protected AppointmentService $appointmentService;

    public function __construct(AppointmentService $appointmentService)
    {
        $this->appointmentService = $appointmentService;
    }

    public function index(Request $request): JsonResponse
    {
        $appointments = $this->appointmentService->getAllByDoctor($request->user()->id);
        return response()->json(AppointmentResource::collection($appointments));
    }

    public function store(StoreAppointmentRequest $request): JsonResponse
    {
        $appointment = $this->appointmentService->store($request->user()->id, $request->validated());
        return response()->json(new AppointmentResource($appointment), 201);
    }

    public function show($id): JsonResponse
    {
        $appointment = $this->appointmentService->getById($id);
        return response()->json(new AppointmentResource($appointment));
    }

    public function update(StoreAppointmentRequest $request, $id): JsonResponse
    {
        $appointment = $this->appointmentService->update($id, $request->validated());
        return response()->json(new AppointmentResource($appointment));
    }

    public function destroy($id): JsonResponse
    {
        $this->appointmentService->delete($id);
        return response()->json(['message' => 'Appointment deleted successfully']);
    }
}

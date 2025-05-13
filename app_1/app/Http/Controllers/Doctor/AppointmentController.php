<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Doctor\StoreAppointmentRequest;
use App\Http\Resources\Doctor\AppointmentResource;
use App\Services\Doctor\AppointmentService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AppointmentController extends Controller
{
    protected AppointmentService $appointmentService;

    public function __construct(AppointmentService $appointmentService)
    {
        $this->appointmentService = $appointmentService;
    }

    public function index(Request $request)
    {
        $appointments = $this->appointmentService->getAll($request);
        return response()->json(AppointmentResource::collection($appointments));
    }

    public function store(StoreAppointmentRequest $request)
    {
        $appointment = $this->appointmentService->create($request->validated());
        return response()->json(new AppointmentResource($appointment), Response::HTTP_CREATED);
    }

    public function show($id)
    {
        $appointment = $this->appointmentService->getById($id);
        if (!$appointment) {
            return response()->json(['error' => 'Appointment not found'], Response::HTTP_NOT_FOUND);
        }
        return response()->json(new AppointmentResource($appointment));
    }

    public function destroy($id)
    {
        $deleted = $this->appointmentService->delete($id);
        if (!$deleted) {
            return response()->json(['error' => 'Appointment not found or already deleted'], Response::HTTP_NOT_FOUND);
        }
        return response()->json(['message' => 'Appointment deleted successfully']);
    }
}

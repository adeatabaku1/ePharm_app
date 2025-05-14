<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Doctor\StorePatientRequest;
use App\Http\Requests\Doctor\UpdatePatientRequest;
use App\Http\Resources\Doctor\PatientResource;
use App\Services\Doctor\PatientService;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    protected $patientService;

    public function __construct(PatientService $patientService)
    {
        $this->patientService = $patientService;
    }

    public function index(Request $request)
    {
        $patients = $this->patientService->getAll($request->user()->id);
        return PatientResource::collection($patients);
    }

    public function show($id)
    {
        $patient = $this->patientService->getById($id);
        return new PatientResource($patient);
    }

    public function store(StorePatientRequest $request)
    {
        $patient = $this->patientService->create($request->validated());
        return new PatientResource($patient);
    }

    public function update(UpdatePatientRequest $request, $id)
    {
        $patient = $this->patientService->update($id, $request->validated());
        return new PatientResource($patient);
    }

    public function destroy($id)
    {
        $this->patientService->delete($id);
        return response()->json(['message' => 'Patient deleted successfully']);
    }
}

<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Doctor\StorePrescriptionRequest;
use App\Http\Requests\Doctor\UpdatePrescriptionRequest;
use App\Http\Resources\PrescriptionResource;
use App\Services\Doctor\PrescriptionService;
use Illuminate\Http\Request;

class PrescriptionController extends Controller
{
    protected $service;

    public function __construct(PrescriptionService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return PrescriptionResource::collection($this->service->getAll());
    }

    public function store(StorePrescriptionRequest $request)
    {
        $prescription = $this->service->store($request->validated());
        return new PrescriptionResource($prescription);
    }

    public function show($id)
    {
        $prescription = $this->service->getById($id);
        return new PrescriptionResource($prescription);
    }

    public function update(UpdatePrescriptionRequest $request, $id)
    {
        $prescription = $this->service->update($id, $request->validated());
        return new PrescriptionResource($prescription);
    }

    public function destroy($id)
    {
        return response()->json([
            'success' => $this->service->delete($id)
        ]);
    }
}

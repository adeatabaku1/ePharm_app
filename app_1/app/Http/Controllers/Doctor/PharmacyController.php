<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Doctor\PharmacyRequest;
use App\Http\Resources\PharmacyResource;
use App\Services\Doctor\PharmacyService;
use Illuminate\Http\Request;

class PharmacyController extends Controller
{
    protected $pharmacyService;

    public function __construct(PharmacyService $pharmacyService)
    {
        $this->pharmacyService = $pharmacyService;
    }

    public function index()
    {
        $pharmacies = $this->pharmacyService->getAll();
        return PharmacyResource::collection($pharmacies);
    }

    public function store(PharmacyRequest $request)
    {
        $pharmacy = $this->pharmacyService->create($request->validated());
        return new PharmacyResource($pharmacy);
    }

    public function show($id)
    {
        $pharmacy = $this->pharmacyService->findById($id);
        return new PharmacyResource($pharmacy);
    }

    public function update(PharmacyRequest $request, $id)
    {
        $pharmacy = $this->pharmacyService->update($id, $request->validated());
        return new PharmacyResource($pharmacy);
    }

    public function destroy($id)
    {
        return response()->json([
            'success' => $this->pharmacyService->delete($id),
            'message' => 'Pharmacy deleted successfully.'
        ]);
    }
}

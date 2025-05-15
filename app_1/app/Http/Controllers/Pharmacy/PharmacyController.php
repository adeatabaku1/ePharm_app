<?php

namespace App\Http\Controllers\Pharmacy;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pharmacy\PharmacyDashboardRequest;
use App\Http\Requests\Pharmacy\PharmacySettingsRequest;
use App\Models\Pharmacy;
use App\Services\Pharmacy\PharmacyService;
use Illuminate\Http\Request;

class PharmacyController extends Controller
{
    protected $pharmacyService;

    public function __construct(PharmacyService $pharmacyService)
    {
        $this->pharmacyService = $pharmacyService;
    }

    /**
     * Get pharmacy dashboard statistics
     * 
     * @param int $pharmacyId
     * @param PharmacyDashboardRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDashboardStats($pharmacyId, PharmacyDashboardRequest $request)
    {
        $stats = $this->pharmacyService->getDashboardStats($pharmacyId, $request->period);
        return response()->json($stats);
    }

    /**
     * Get pharmacy settings
     * 
     * @param int $pharmacyId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSettings($pharmacyId)
    {
        $settings = $this->pharmacyService->getSettings($pharmacyId);
        return response()->json($settings);
    }

    /**
     * Update pharmacy settings
     * 
     * @param int $pharmacyId
     * @param PharmacySettingsRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateSettings($pharmacyId, PharmacySettingsRequest $request)
    {
        $updatedSettings = $this->pharmacyService->updateSettings($pharmacyId, $request->validated());
        return response()->json($updatedSettings);
    }
}

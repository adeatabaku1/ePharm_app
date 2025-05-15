<?php

namespace App\Http\Controllers\Pharmacy;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pharmacy\BillingFilterRequest;
use App\Services\Pharmacy\BillingService;
use Illuminate\Http\Request;

class BillingController extends Controller
{
    protected $billingService;

    public function __construct(BillingService $billingService)
    {
        $this->billingService = $billingService;
    }

    /**
     * Get sales for a pharmacy
     * 
     * @param int $pharmacyId
     * @param BillingFilterRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSales($pharmacyId, BillingFilterRequest $request)
    {
        $sales = $this->billingService->getSales(
            $pharmacyId, 
            $request->search, 
            $request->date_filter, 
            $request->payment_method
        );
        
        return response()->json($sales);
    }

    /**
     * Get bills for a pharmacy
     * 
     * @param int $pharmacyId
     * @param BillingFilterRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getBills($pharmacyId, BillingFilterRequest $request)
    {
        $bills = $this->billingService->getBills(
            $pharmacyId, 
            $request->search, 
            $request->date_filter, 
            $request->payment_method
        );
        
        return response()->json($bills);
    }

    /**
     * Get billing statistics for a pharmacy
     * 
     * @param int $pharmacyId
     * @param BillingFilterRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getBillingStats($pharmacyId, BillingFilterRequest $request)
    {
        $stats = $this->billingService->getBillingStats(
            $pharmacyId, 
            $request->date_filter
        );
        
        return response()->json($stats);
    }

    /**
     * Create a new bill
     * 
     * @param int $pharmacyId
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createBill($pharmacyId, Request $request)
    {
        $bill = $this->billingService->createBill($pharmacyId, $request->all());
        return response()->json($bill, 201);
    }
}

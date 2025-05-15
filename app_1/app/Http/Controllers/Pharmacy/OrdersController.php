<?php

namespace App\Http\Controllers\Pharmacy;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pharmacy\OrderFilterRequest;
use App\Http\Requests\Pharmacy\OrderStatusRequest;
use App\Services\Pharmacy\OrderService;
use Illuminate\Http\Request;

class OrdersController extends Controller
{
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    /**
     * Get orders for a pharmacy with optional filters
     * 
     * @param int $pharmacyId
     * @param OrderFilterRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getOrders($pharmacyId, OrderFilterRequest $request)
    {
        $orders = $this->orderService->getOrders(
            $pharmacyId, 
            $request->search, 
            $request->status,
            $request->date_filter
        );
        
        return response()->json($orders);
    }

    /**
     * Get a specific order
     * 
     * @param int $pharmacyId
     * @param int $orderId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getOrder($pharmacyId, $orderId)
    {
        $order = $this->orderService->getOrder($pharmacyId, $orderId);
        return response()->json($order);
    }

    /**
     * Update order status
     * 
     * @param int $pharmacyId
     * @param int $orderId
     * @param OrderStatusRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateOrderStatus($pharmacyId, $orderId, OrderStatusRequest $request)
    {
        $order = $this->orderService->updateOrderStatus(
            $pharmacyId, 
            $orderId, 
            $request->status
        );
        
        return response()->json($order);
    }

    /**
     * Get order statistics
     * 
     * @param int $pharmacyId
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getOrderStats($pharmacyId, Request $request)
    {
        $stats = $this->orderService->getOrderStats(
            $pharmacyId, 
            $request->date_filter
        );
        
        return response()->json($stats);
    }
}

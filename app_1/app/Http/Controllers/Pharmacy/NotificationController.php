<?php

namespace App\Http\Controllers\Pharmacy;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pharmacy\NotificationFilterRequest;
use App\Services\Pharmacy\NotificationService;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Get notifications for a pharmacy
     * 
     * @param int $pharmacyId
     * @param NotificationFilterRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getNotifications($pharmacyId, NotificationFilterRequest $request)
    {
        $notifications = $this->notificationService->getNotifications(
            $pharmacyId, 
            $request->type,
            $request->read_status
        );
        
        return response()->json($notifications);
    }

    /**
     * Mark notification as read
     * 
     * @param int $pharmacyId
     * @param int $notificationId
     * @return \Illuminate\Http\JsonResponse
     */
    public function markAsRead($pharmacyId, $notificationId)
    {
        $notification = $this->notificationService->markAsRead($pharmacyId, $notificationId);
        return response()->json($notification);
    }

    /**
     * Mark all notifications as read
     * 
     * @param int $pharmacyId
     * @return \Illuminate\Http\JsonResponse
     */
    public function markAllAsRead($pharmacyId)
    {
        $this->notificationService->markAllAsRead($pharmacyId);
        return response()->json(['message' => 'All notifications marked as read']);
    }

    /**
     * Get notification count
     * 
     * @param int $pharmacyId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getNotificationCount($pharmacyId)
    {
        $count = $this->notificationService->getNotificationCount($pharmacyId);
        return response()->json(['count' => $count]);
    }
}

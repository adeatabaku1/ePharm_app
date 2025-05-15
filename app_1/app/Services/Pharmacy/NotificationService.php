<?php

namespace App\Services\Pharmacy;

use App\Models\Notification;
use Illuminate\Support\Facades\DB;

class NotificationService
{
    /**
     * Get notifications for a pharmacy with optional filters
     * 
     * @param int $pharmacyId
     * @param string|null $type
     * @param string|null $readStatus
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getNotifications($pharmacyId, $type = null, $readStatus = null)
    {
        $query = Notification::where('notifiable_id', $pharmacyId)
            ->where('notifiable_type', 'App\Models\Pharmacy');
            
        // Apply type filter
        if ($type) {
            $query->where('type', $type);
        }
        
        // Apply read status filter
        if ($readStatus !== null) {
            $isRead = $readStatus === 'read';
            $query->where('read_at', $isRead ? '!=' : '=', null);
        }
        
        return $query->orderBy('created_at', 'desc')->paginate(15);
    }
    
    /**
     * Mark notification as read
     * 
     * @param int $pharmacyId
     * @param int $notificationId
     * @return Notification
     */
    public function markAsRead($pharmacyId, $notificationId)
    {
        $notification = Notification::where('id', $notificationId)
            ->where('notifiable_id', $pharmacyId)
            ->where('notifiable_type', 'App\Models\Pharmacy')
            ->firstOrFail();
            
        if (!$notification->read_at) {
            $notification->read_at = now();
            $notification->save();
        }
        
        return $notification;
    }
    
    /**
     * Mark all notifications as read
     * 
     * @param int $pharmacyId
     * @return int
     */
    public function markAllAsRead($pharmacyId)
    {
        return Notification::where('notifiable_id', $pharmacyId)
            ->where('notifiable_type', 'App\Models\Pharmacy')
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
    }
    
    /**
     * Get notification count
     * 
     * @param int $pharmacyId
     * @return int
     */
    public function getNotificationCount($pharmacyId)
    {
        return Notification::where('notifiable_id', $pharmacyId)
            ->where('notifiable_type', 'App\Models\Pharmacy')
            ->whereNull('read_at')
            ->count();
    }
}

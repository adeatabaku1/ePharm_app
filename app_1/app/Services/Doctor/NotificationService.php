<?php
namespace App\Services\Doctor;

use App\Models\Notification;

class NotificationService
{
    public function getAll($userId)
    {
        return Notification::where('user_id', $userId)->latest()->get();
    }

    public function create(array $data)
    {
        return Notification::create($data);
    }

    public function getById($id)
    {
        return Notification::findOrFail($id);
    }

    public function markAsRead($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->update(['is_read' => true]);
    }

    public function delete($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->delete();
    }
}

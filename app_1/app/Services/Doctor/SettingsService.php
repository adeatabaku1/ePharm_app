<?php

namespace App\Services\Doctor;
use App\Models\Doctor;
use Illuminate;

class SettingsService
{
    public function updateProfile($user, $data)
    {
        $user->update($data);

        return [
            'success' => true,
            'message' => 'Profile updated successfully.',
            'data' => $user
        ];
    }

    public function updatePassword($user, $data)
    {
        if (!Hash::check($data['current_password'], $user->password)) {
            return [
                'success' => false,
                'message' => 'Current password does not match.',
                'data' => null
            ];
        }

        $user->password = Hash::make($data['new_password']);
        $user->save();

        return [
            'success' => true,
            'message' => 'Password updated successfully.',
            'data' => null
        ];
    }
}

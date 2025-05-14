<?php

namespace App\Services\Doctor;

use App\Models\Doctor;
use Illuminate\Support\Facades\DB;

class DoctorService
{
    public function getProfile($userId): Doctor
    {
        return Doctor::with('user')->where('user_id', $userId)->firstOrFail();
    }

    public function updateProfile($userId, array $data): Doctor
    {
        $doctor = Doctor::where('user_id', $userId)->firstOrFail();

        DB::transaction(function () use ($doctor, $data) {
            $doctor->user->update([
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
            ]);

            $doctor->update([
                'license_number' => $data['license_number'],
                'specialization' => $data['specialization'],
            ]);
        });

        return $doctor->refresh()->load('user');
    }
}

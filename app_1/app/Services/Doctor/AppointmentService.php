<?php

namespace App\Services\Doctor;

use App\Models\Appointment;

class AppointmentService
{
    public function getAllByDoctor($doctorId)
    {
        return Appointment::where('doctor_id', $doctorId)->latest()->get();
    }

    public function getById($id)
    {
        return Appointment::findOrFail($id);
    }

    public function store($doctorId, array $data)
    {
        $data['doctor_id'] = $doctorId;
        return Appointment::create($data);
    }

    public function update($id, array $data)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->update($data);
        return $appointment;
    }

    public function delete($id)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->delete();
    }
}

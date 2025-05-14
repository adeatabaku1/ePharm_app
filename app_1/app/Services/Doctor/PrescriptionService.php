<?php

namespace App\Services\Doctor;

use App\Models\Prescription;

class PrescriptionService
{
    public function getAll()
    {
        return Prescription::with(['doctor.user', 'patient.user'])->latest()->get();
    }

    public function getById($id)
    {
        return Prescription::with(['doctor.user', 'patient.user'])->findOrFail($id);
    }

    public function store(array $data)
    {
        return Prescription::create($data);
    }

    public function update($id, array $data)
    {
        $prescription = Prescription::findOrFail($id);
        $prescription->update($data);
        return $prescription;
    }

    public function delete($id)
    {
        $prescription = Prescription::findOrFail($id);
        return $prescription->delete();
    }
}

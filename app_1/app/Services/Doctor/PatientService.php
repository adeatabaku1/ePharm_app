<?php

namespace App\Services\Doctor;

use App\Models\Patient;

class PatientService
{
    public function getAll($doctorId)
    {
        return Patient::whereHas('doctorPatients', function ($query) use ($doctorId) {
            $query->where('doctor_id', $doctorId);
        })->get();
    }

    public function getById($id)
    {
        return Patient::findOrFail($id);
    }

    public function create(array $data)
    {
        return Patient::create($data);
    }

    public function update($id, array $data)
    {
        $patient = Patient::findOrFail($id);
        $patient->update($data);
        return $patient;
    }

    public function delete($id)
    {
        $patient = Patient::findOrFail($id);
        $patient->delete();
    }
}

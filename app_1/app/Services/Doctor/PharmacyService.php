<?php

namespace App\Services\Doctor;

use App\Models\Pharmacy;

class PharmacyService
{
    public function getAll()
    {
        return Pharmacy::all();
    }

    public function create(array $data)
    {
        return Pharmacy::create($data);
    }

    public function findById($id)
    {
        return Pharmacy::findOrFail($id);
    }

    public function update($id, array $data)
    {
        $pharmacy = Pharmacy::findOrFail($id);
        $pharmacy->update($data);
        return $pharmacy;
    }

    public function delete($id)
    {
        $pharmacy = Pharmacy::findOrFail($id);
        return $pharmacy->delete();
    }
}

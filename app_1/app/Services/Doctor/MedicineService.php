<?php

namespace App\Services\Doctor;

use App\Models\Medicine;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class MedicineService
{
    public function getAll()
    {
        return Medicine::where('tenant_id', auth()->user()->tenant_id)->get();
    }

    public function getById($id)
    {
        return Medicine::where('tenant_id', auth()->user()->tenant_id)->findOrFail($id);
    }

    public function create(array $data)
    {
        $data['tenant_id'] = auth()->user()->tenant_id;
        $data['created_by'] = auth()->id();
        return Medicine::create($data);
    }

    public function update($id, array $data)
    {
        $medicine = $this->getById($id);
        $data['updated_by'] = auth()->id();
        $medicine->update($data);
        return $medicine;
    }

    public function delete($id)
    {
        $medicine = $this->getById($id);
        $medicine->delete();
    }
}

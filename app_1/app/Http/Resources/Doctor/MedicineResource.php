<?php

namespace App\Http\Resources\Doctor;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MedicineResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => (string) $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'stock' => $this->stock_quantity,
            'expiryDate' => $this->expire_date,
            'dosage' => $this->dosage,
            'pharmacyId' => (string) $this->pharmacy_id,
        ];
    }

}

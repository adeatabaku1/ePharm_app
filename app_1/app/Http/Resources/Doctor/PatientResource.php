<?php

namespace App\Http\Resources\Doctor;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PatientResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'license_number' => $this->license_number,
            'birthdate' => $this->birthdate,
            'gender' => $this->gender,
            'address' => $this->address,
            'created_at' => $this->created_at,
        ];
    }
}

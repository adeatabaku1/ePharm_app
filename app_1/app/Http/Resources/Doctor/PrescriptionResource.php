<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PrescriptionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'doctor' => $this->doctor->user->name ?? null,
            'patient' => $this->patient->user->name ?? null,
            'diagnosis' => $this->diagnosis,
            'notes' => $this->notes,
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}

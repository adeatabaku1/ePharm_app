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
            'status' => $this->status ?? 'active', // default or db value
            'date' => $this->created_at->toDateString(),
            'medications' => $this->items->map(function ($item) {
                return [
                    'name' => $item->name,
                    'dosage' => $item->dosage,
                    'frequency' => $item->frequency,
                    'duration' => $item->duration,
                ];
            }),
        ];
    }

}

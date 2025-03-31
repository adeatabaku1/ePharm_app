<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedicationIntakeReminder extends Model
{
    protected $fillable = [
        'patient_id',
        'prescription_item_id',
        'scheduled_time',
        'sent_at',
        'created_at',
    ];
}


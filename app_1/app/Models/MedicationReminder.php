<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedicationReminder extends Model
{
    protected $fillable = [
        'patient_id',
        'prescription_item_id',
        'reminder_date',
        'sent',
        'created_at',
    ];
}

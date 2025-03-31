<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedicineLog extends Model
{
    protected $fillable = [
        'medicine_id',
        'user_id',
        'action',
        'description',
        'quantity_change',
        'created_at',
    ];
}

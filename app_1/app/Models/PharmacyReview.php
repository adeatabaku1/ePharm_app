<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PharmacyReview extends Model
{
    protected $fillable = [
        'patient_id',
        'pharmacy_id',
        'rating',
        'comment',
        'created_at',
    ];
}

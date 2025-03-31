<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    protected $fillable = [
        'pharmacy_id',
        'patient_id',
        'sale_id',
        'payment_method',
        'total_price',
        'created_at',
    ];

    public function pharmacy()
    {
        return $this->belongsTo(Pharmacy::class);
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}

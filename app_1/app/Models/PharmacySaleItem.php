<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class PharmacySaleItem extends Model
{
    protected $fillable = ['sale_id', 'medicine_id', 'quantity', 'unit_price', 'subtotal'];

    public function sale()
    {
        return $this->belongsTo(PharmacySale::class);
    }
}

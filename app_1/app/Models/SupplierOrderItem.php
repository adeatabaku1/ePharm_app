<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupplierOrderItem extends Model
{
    protected $fillable = [
        'supplier_order_id',
        'medication_name',
        'quantity',
        'unit_price',
        'subtotal',
    ];

    public function supplierOrder()
    {
        return $this->belongsTo(SupplierOrder::class);
    }
}

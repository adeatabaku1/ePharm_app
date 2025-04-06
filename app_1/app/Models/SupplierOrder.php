<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class SupplierOrder extends Model
{
    protected $fillable = ['tenant_id', 'supplier_id', 'order_date', 'status', 'total_cost', 'created_by'];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function items()
    {
        return $this->hasMany(SupplierOrderItem::class);
    }
}

<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class PrescriptionDelivery extends Model
{
    protected $fillable = ['prescription_id', 'pharmacy_id', 'delivery_type', 'status', 'discount_applied', 'delivered_at'];

    public function prescription()
    {
        return $this->belongsTo(Prescription::class);
    }

    public function pharmacy()
    {
        return $this->belongsTo(Tenant::class, 'pharmacy_id');
    }
}

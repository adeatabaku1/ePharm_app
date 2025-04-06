<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class PharmacySale extends Model
{
    protected $fillable = ['pharmacy_id', 'patient_id', 'total_amount', 'credit_awarded', 'sale_date', 'processed_by'];

    public function items()
    {
        return $this->hasMany(PharmacySaleItem::class);
    }
}

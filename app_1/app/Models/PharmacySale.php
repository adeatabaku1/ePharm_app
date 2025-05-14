<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class PharmacySale extends Model
{
    protected $fillable = ['pharmacy_id', 'patient_id', 'total_amount', 'credit_awarded', 'sale_date', 'processed_by'];

    protected $casts = [
        'total_amount' => 'float',
        'credit_awarded'=> 'integer',
        'sale_date'    => 'date',
    ];

    public function pharmacy()
    {
        return $this->belongsTo(Pharmacy::class);
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
    public function items()
    {
        return $this->hasMany(PharmacySaleItem::class);
    }
}

<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class PharmacyCredit extends Model
{
    protected $fillable = ['pharmacy_id', 'patient_id', 'credit_points', 'earned_at'];

    public function pharmacy()
    {
        return $this->belongsTo(Tenant::class, 'pharmacy_id');
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}

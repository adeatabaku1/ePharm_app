<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Prescription extends Model
{
    protected $fillable = ['tenant_id', 'doctor_id', 'patient_id', 'diagnosis', 'notes', 'is_sent_to_patient', 'discount_code_id'];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function items()
    {
        return $this->hasMany(PrescriptionItem::class);
    }

    public function delivery()
    {
        return $this->hasOne(PrescriptionDelivery::class);
    }

    public function discountCode()
    {
        return $this->belongsTo(DiscountCode::class);
    }
}

<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class PrescriptionItem extends Model
{
    protected $fillable = ['prescription_id', 'medication_name', 'dosage', 'capsule_count', 'frequency_per_day', 'intake_times', 'duration_days'];

    protected $casts = [
        'intake_times' => 'array',
    ];

    public function prescription()
    {
        return $this->belongsTo(Prescription::class);
    }
}

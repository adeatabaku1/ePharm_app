<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'license_number',
        'specialization',
        'is_verified',
    ];

    public $timestamps = false;

    protected $casts = [
        'is_verified' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function prescriptions()
    {
        return $this->hasMany(Prescription::class);
    }
    public function patients()
    {
        return $this->belongsToMany(Patient::class, 'doctor_patients');
    }

}

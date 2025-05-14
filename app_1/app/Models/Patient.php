<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'license_number',
        'birthdate',
        'gender',
        'address',
    ];

    protected $casts = [
        'birthdate' => 'date',
    ];

    public $timestamps = false; // if you only have created_at

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function prescriptions()
    {
        return $this->hasMany(Prescription::class);
    }
    public function doctors()
    {
        return $this->belongsToMany(Doctor::class, 'doctor_patients');
    }

}

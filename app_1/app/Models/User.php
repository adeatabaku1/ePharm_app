<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'username',
        'password',
        'phone',
        'pharmacy_id',   // links owner or pharmacist to that Pharmacy
        'role',          // e.g. 'pharmacy_owner', 'pharmacist', 'patient', 'doctor'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'phone_verified'    => 'boolean',
    ];

    // ————————————— Relationships —————————————

    public function pharmacy()
    {
        return $this->belongsTo(Pharmacy::class);
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
    public function patient()   { return $this->hasOne(Patient::class); }
    public function doctor()    { return $this->hasOne(Doctor::class); }
    public function pharmacist(){ return $this->hasOne(Pharmacist::class); }

    // ————————————— Helpers —————————————

    /** Quick check of the simple role column */
    public function isRole(string $role): bool
    {
        return $this->role === $role;
    }
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles');
    }
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

}

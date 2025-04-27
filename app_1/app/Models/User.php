<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'username',
        'password',
        'phone',
        'user_type',
        'tenant_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // ————————————— Relationships —————————————
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function roles()
    {
        return $this
            ->belongsToMany(Role::class, 'user_roles')
            ->withTimestamps();
    }

    public function patient()
    {
        return $this->hasOne(Patient::class);
    }

    public function doctor()
    {
        return $this->hasOne(Doctor::class);
    }

    public function pharmacist()
    {
        return $this->hasOne(Pharmacist::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    // ————————————— Helpers —————————————
    /** Check if user has a given role */
    public function hasRole(string $role): bool
    {
        return $this->roles->contains('name', $role);
    }

    /** Check if user has any of the given roles */
    public function hasAnyRole(array $roles): bool
    {
        return $this->roles->whereIn('name', $roles)->isNotEmpty();
    }
}

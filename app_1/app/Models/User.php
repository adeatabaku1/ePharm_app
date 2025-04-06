<?php
namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $fillable = ['name', 'email', 'username', 'password', 'tenant_id', 'phone', 'user_type'];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles');
    }

    public function pharmacist()
    {
        return $this->hasOne(Pharmacist::class);
    }
}

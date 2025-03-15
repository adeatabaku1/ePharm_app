<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['name', 'email', 'phone', 'address'];


    /**
     * Define relationship: A Tenant can have multiple Users (Doctors, Pharmacists, etc.).
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }
}

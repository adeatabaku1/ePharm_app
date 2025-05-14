<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pharmacy extends Model
{
    protected $fillable = [
        'arbk_name',
        'trade_name',
        'business_type',
        'registration_num',
        'business_num',
        'fiscal_num',
        'employee_count',
        'registration_date',
        'municipality',
        'address',
        'phone',
        'email',
        'capital',
        'arbk_status',
        'verified_at',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function sales()
    {
        return $this->hasMany(PharmacySale::class);
    }

    public function credits()
    {
        return $this->hasMany(PharmacyCredit::class);
    }

    public function reviews()
    {
        return $this->hasMany(PharmacyReview::class);
    }

}

<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $fillable = ['tenant_id', 'name', 'contact_email', 'phone', 'address'];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function orders()
    {
        return $this->hasMany(SupplierOrder::class);
    }
}

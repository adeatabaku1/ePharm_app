<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{

    use HasFactory;

    protected $fillable = ['name', 'type', 'is_verified'];

    protected $casts = [
        'is_verified' => 'boolean',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function pharmacies()
    {
        return $this->hasMany(Pharmacy::class);
    }

    public function medicines()
    {
        return $this->hasMany(Medicine::class);
    }
    public function supplierOrders()
    {
        return $this->hasMany(SupplierOrder::class);
    }

    public function prescriptions()
    {
        return $this->hasMany(Prescription::class);
    }

}

<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class DiscountCode extends Model
{
    protected $fillable = ['code', 'percent', 'usage_limit', 'expires_at'];

    public function prescriptions()
    {
        return $this->hasMany(Prescription::class);
    }
}

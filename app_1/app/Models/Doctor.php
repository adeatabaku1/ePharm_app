<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    protected $fillable = ['user_id', 'license_number', 'specialization', 'is_verified'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

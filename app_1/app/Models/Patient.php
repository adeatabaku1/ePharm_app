<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $fillable = ['user_id', 'license_number', 'birthdate', 'gender', 'address'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

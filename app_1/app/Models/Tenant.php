<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{

    use HasFactory;

    protected $fillable = ['name', 'type', 'is_verified'];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}

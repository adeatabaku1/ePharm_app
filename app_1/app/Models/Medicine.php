<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    protected $fillable = ['tenant_id', 'name', 'description', 'price', 'stock_quantity', 'expire_date', 'created_by', 'updated_by'];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}

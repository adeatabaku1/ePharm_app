<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    protected $fillable = ['tenant_id', 'name', 'description', 'price', 'stock_quantity', 'expire_date', 'created_by', 'updated_by'];

    protected $casts = [
        'price'          => 'float',
        'stock_quantity' => 'integer',
        'expire_date'    => 'date',
    ];
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
    public function sales()
    {
        return $this->hasMany(PharmacySaleItem::class);
    }

    public function logs()
    {
        return $this->hasMany(MedicineLog::class);
    }
}

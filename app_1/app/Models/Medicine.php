<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property float $price
 * @property int $stock_quantity
 * @property string $expire_date
 * @property string $dosage
 * @property int $pharmacy_id
 * @property int $tenant_id
 * @property int $created_by
 * @property int $updated_by
 */

class Medicine extends Model
{
    protected $fillable = [
        'tenant_id',
        'name',
        'description',
        'price',
        'stock_quantity',
        'expire_date',
        'dosage',
        'pharmacy_id',
        'created_by',
        'updated_by'
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}

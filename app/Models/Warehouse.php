<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    use HasFactory;
    protected $fillable = [
        'customer_id',
        'fullname',
        'phone',
        'address',
        'code_cn',
        'code_vn',
        'date',
        'name_cn',
        'name_vn',
        'weight',
        'quantity',
        'quantity_kien',
        'price',
        'priceTE',
        'total_price',
        'type',
        'user_id',
        'created_at',
        'updated_at',
        'deleted_at',
        'publish',
        'user_deteled_id',
        'status_packaging_truck',
        'date_packaging_truck',
    ];
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
    public function customer()
    {
        return $this->hasOne(Customer::class, 'id', 'customer_id');
    }
    public function warehouse_relationships()
    {
        return $this->hasMany(WarehouseRelationships::class, 'warehouse_id');
    }
    public function packaging_relationships()
    {
        return $this->hasOne(PackagingRelationships::class, 'warehouse_id');
    }
    public function delivery_relationships()
    {
        return $this->hasOne(DeliveryRelationships::class, 'code', 'code_cn');
    }
}

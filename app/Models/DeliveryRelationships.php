<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryRelationships extends Model
{
    use HasFactory;
    protected $fillable = [
        'delivery_id', 'code', 'created_at', 'updated_at','note','weight'
    ];
    public function deliveries()
    {
        return $this->hasOne(Delivery::class, 'id', 'delivery_id');
    }
    public function warehouses()
    {
        return $this->hasOne(Warehouse::class, 'code_cn', 'code');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    use HasFactory;
    protected $fillable = [
        'code', 'weight', 'customer_id', 'created_at', 'updated_at', 'user_id', 'products', 'price', 'status', 'fee', 'shipping', 'code_merge','advanced'
    ];
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
    public function customer()
    {
        return $this->hasOne(Customer::class, 'id', 'customer_id');
    }
    public function delivery_histories()
    {
        return $this->hasMany(DeliveryHistory::class);
    }
    public function delivery_relationships()
    {
        return $this->hasMany(DeliveryRelationships::class);
    }
}

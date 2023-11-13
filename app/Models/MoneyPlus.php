<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MoneyPlus extends Model
{
    use HasFactory;
    protected $fillable = ['code', 'customer_id', 'user_id', 'price', 'status',  'created_at', 'updated_at'];
    public function customer()
    {
        return $this->hasOne(Customer::class, 'id', 'customer_id');
    }
}

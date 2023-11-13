<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipping extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',  'weight_min', 'weight_max', 'price', 'customer_id', 'user_id', 'created_at',  'updated_at'
    ];
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}

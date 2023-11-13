<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerStatusHistory extends Model
{
    use HasFactory;
    protected $fillable = [
        'message',
        'customer_order_id',
        'user_id',
        'created_at',
        'updated_at'
    ];
}

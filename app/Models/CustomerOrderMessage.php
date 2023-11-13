<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerOrderMessage extends Model
{
    use HasFactory;
    protected $fillable = [
        'type',
        'customer_order_id',
        'message',
        'user_id',
        'created_at',
        'updated_at',
    ];
}

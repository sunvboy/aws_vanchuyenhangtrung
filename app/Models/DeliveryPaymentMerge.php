<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryPaymentMerge extends Model
{
    use HasFactory;
    protected $fillable = [
        'status', 'code', 'price', 'ids', 'user_id', 'created_at', 'updated_at', 'payment'
    ];
}

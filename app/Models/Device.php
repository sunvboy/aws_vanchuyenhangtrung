<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    protected $fillable = [
        'device_token', 'device_type', 'customer_id', 'created_at', 'updated_at'
    ];
    use HasFactory;
}

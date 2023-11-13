<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiLog extends Model
{
    use HasFactory;
    protected $fillable = [
        'data', 'customer_id', 'created_at', 'module','updated_at'
    ];
}

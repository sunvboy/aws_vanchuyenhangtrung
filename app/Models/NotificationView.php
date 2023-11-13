<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationView extends Model
{
    use HasFactory;
    protected $fillable = [
        'notification_id', 'customer_id', 'created_at', 'updated_at'
    ];
}

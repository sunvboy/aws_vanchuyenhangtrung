<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'title', 'body', 'type', 'customer_order_id', 'customer_id', 'created_at', 'updated_at', 'user_id', 'content', 'customer_system_id', 'userid_deleted', 'deleted_at','delivery_id','send'
    ];
    use HasFactory;
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
    public function customer()
    {
        return $this->hasOne(Customer::class, 'id', 'customer_id');
    }
    public function notification_views()
    {
        return $this->hasOne(NotificationView::class,  'notification_id');
    }
}

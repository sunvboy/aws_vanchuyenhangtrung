<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerOrder extends Model
{
    use HasFactory;
    protected $fillable = [
        'code',
        'status',
        'customer_id',
        'title',
        'quantity',
        'weight',
        'price',
        'total',
        'total_price_old',
        'cny',
        'links',
        'images',
        'note',
        'created_at',
        'updated_at',
        'deleted_at',
        'fee',
        'total_price_cny_final',
        'total_price_vnd_final',
        'message',
        'device',
        'status_return',
        'links_return',
        'images_return',
        'price_return',
        'price_return_success',
        'message_return',
        'date_return',
        'user_id_return',
        'mavandon',

    ];
    public function customer()
    {
        return $this->hasOne(Customer::class, 'id', 'customer_id');
    }
    public function user_return()
    {
        return $this->hasOne(User::class, 'id', 'user_id_return');
    }
    public function customer_status_histories()
    {
        return $this->hasMany(CustomerStatusHistory::class, 'customer_order_id');
    }
    public function customer_order_messages()
    {
        return $this->hasMany(CustomerOrderMessage::class, 'customer_order_id');
    }
}

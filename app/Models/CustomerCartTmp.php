<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerCartTmp extends Model
{
    use HasFactory;
    protected $fillable = [
        'rowid',
        'customer_id',
        'title',
        'weight',
        'quantity',
        'price',
        'total',
        'links',
        'images',
        'note',
        'created_at',
        'updated_at',
    ];
}

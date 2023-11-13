<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Packaging extends Model
{
    use HasFactory;
    protected $fillable = [
        'customer_id',
        'code',
        'total_weight',
        'products',
        'user_id',
        'created_at',
        'updated_at',
        'deleted_at',
        'value_weight',
        'code_vn',
        'value_quantity',
    ];
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
    public function customer()
    {
        return $this->hasOne(Customer::class, 'id', 'customer_id');
    }
    public function packaging_relationships()
    {
        return $this->hasMany(PackagingRelationships::class, 'packaging_id')->where('deleted_at',null);
    }
    public function packaging_v_n_s()
    {
        return $this->hasOne(PackagingVN::class, 'packaging_code', 'code')->where('deleted_at',null);
    }
    public function packaging_trucks()
    {
        return $this->hasOne(PackagingTruck::class, 'packaging_code', 'code')->where('deleted_at',null);
    }
}

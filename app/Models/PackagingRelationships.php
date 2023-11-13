<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackagingRelationships extends Model
{
    use HasFactory;
    public function warehouses()
    {
        return $this->hasOne(Warehouse::class, 'code_cn', 'code');
    }
    public function warehouses_china()
    {
        return $this->hasOne(Warehouse::class, 'code_cn', 'code');
    }
    public function warehouses_vietnam()
    {
        return $this->hasOne(Warehouse::class, 'code_vn', 'code_vn');
    }
    public function packagings()
    {
        return $this->hasOne(Packaging::class, 'id', 'packaging_id');
    }
}

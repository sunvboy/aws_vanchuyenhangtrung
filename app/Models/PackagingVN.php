<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackagingVN extends Model
{
    use HasFactory;
    protected $fillable = [
        'packaging_code',
        'user_id',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    public function packagings()
    {
        return $this->hasOne(Packaging::class, 'code', 'packaging_code');
    }
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}

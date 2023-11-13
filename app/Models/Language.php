<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    use HasFactory;
    protected $fillable = [
        'china', 'vietnamese', 'created_at', 'updated_at', 'user_id'
    ];
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}

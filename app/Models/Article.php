<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;
    protected $fillable = [
        'title', 'slug', 'catalogue_id', 'catalogue', 'image', 'content', 'description', 'meta_title', 'meta_description', 'userid_created', 'userid_updated', 'created_at', 'updated_at', 'publish', 'order', 'alanguage', 'products'
    ];
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'userid_created');
    }
    public function relationships()
    {
        return $this->hasMany(CataloguesRelationship::class, 'moduleid');
    }
    public function catalogues()
    {
        return $this->hasOne(CategoryArticle::class, 'id', 'catalogue_id');
    }
}

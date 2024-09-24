<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name', 'slug', 'description', 'content', 'price', 'discount_price', 'quantity',
        'category_id', 'brand_id', 'image', 'is_active', 'is_featured', 'is_new', 'views', 'published_at'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

}

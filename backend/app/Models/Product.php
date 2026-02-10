<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name', 'slug', 'short_description', 'full_description', 'price', 'tag',
        'color_hex', 'weight', 'mill', 'composition', 'construction', 'treatment',
        'sanforization', 'sizing_info', 'shipping_info', 'care_instructions',
        'sizes_available', 'stock_quantity', 'is_active', 'is_featured',
        'meta_title', 'meta_description'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'sizes_available' => 'array',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
    ];

    protected $appends = ['main_image_url'];

    // Relationships
    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Accessors
    public function getMainImageUrlAttribute()
    {
        $mainImage = $this->images()->where('image_type', 'main')->first();
        return $mainImage ? asset('storage/' . $mainImage->image_path) : null;
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeWithTag($query, $tag)
    {
        return $query->where('tag', $tag);
    }

    // Automatically generate slug
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name);
            }
        });
    }
}

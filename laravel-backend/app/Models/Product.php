<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Product extends Model
{
    use SoftDeletes; // enables deleted_at

    protected $fillable = ['name', 'sku', 'alias', 'price','regular_price', 'status'];
  
    protected $appends = ['average_rating']; 

    // A product belongs to many categories
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_product');
    }

    // A product has many images
    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }
      // Relation: primary image only
    public function primaryImage()
    {
        return $this->hasOne(ProductImage::class)->where('is_primary', 1);
    }
    public function ratings()
{
    return $this->hasMany(Rating::class);
}

public function averageRating()
{
    return round((float) $this->ratings()->avg('rating'), 1) ?? 0;
}

public function getAverageRatingAttribute()
    {
        return $this->averageRating();
    }

}

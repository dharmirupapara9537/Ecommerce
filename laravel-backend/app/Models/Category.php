<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Category extends Model
{
    use HasFactory;
    use SoftDeletes;
    // Table name (optional if same as plural model name)
    protected $table = 'categories';

    // Fillable fields
    protected $fillable = [
        'name',
        'image',
        'status',
    ];
    // A category has many products (many-to-many)
    public function products()
    {
        return $this->belongsToMany(Product::class, 'category_product');
    }
}

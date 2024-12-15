<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';

    // Allow mass assignment for these fields
    protected $fillable = ['name', 'brand_id']; // Include 'brand_id' since it's being used

    /**
     * Define a relationship between Category and Product.
     * Each category can have many products.
     */
    public function products()
    {
        return $this->hasMany(Product::class); // Assuming the 'products' table has a 'category_id' foreign key
    }

    /**
     * Define a relationship between Category and Brand.
     * Each category belongs to one brand.
     */
    public function brand()
    {
        return $this->belongsTo(Brand::class); // Make sure this is defined as belongsTo
    }
}

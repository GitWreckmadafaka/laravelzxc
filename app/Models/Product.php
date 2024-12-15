<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'category_id',
        'brand_id',
        'color_id',
        'price',
        'description',
        'image_url',
        'stock',
    ];


    public function color()
    {
        return $this->belongsTo(Color::class);
    }


    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }


    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_items')->withPivot('quantity');
    }
        public function users()
    {
        return $this->belongsToMany(User::class, 'wishlist_product', 'product_id', 'user_id');
    }
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'product_name', 'name');
    }


}

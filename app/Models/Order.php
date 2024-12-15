<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'full_name',
        'email',
        'shipping_address',
        'phone',
        'payment_method',
        'payment_status',
        'total_price',
    ];

       public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
        public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }
}

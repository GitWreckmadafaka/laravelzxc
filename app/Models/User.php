<?php

namespace App\Models;
use App\Models\Product;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;
    public function wishlist()
    {
        return $this->belongsToMany(Product::class, 'wishlist_product', 'user_id', 'product_id');
    }

    protected $fillable = [
        'name',
        'email',
        'password',
        'age',
        'birthdate',
        'gender',
        'active',
        'last_login',
        'email_verified_at',
        'is_admin',
        'github_id'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $dates = [
        'last_login',
    ];

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }
}

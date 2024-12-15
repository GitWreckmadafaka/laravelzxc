<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;

    // Fillable fields for the brand
    protected $fillable = ['name', 'category_id'];

    // Relationship: A brand belongs to a category
    public function category()
    {
        return $this->belongsTo(Category::class); // Inverse of 'hasMany' in Category model
    }
}

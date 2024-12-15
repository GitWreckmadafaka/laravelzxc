<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    // Specify the table if it's not the plural form of the model name
    protected $table = 'sales'; // Only needed if your table is named something different

    // Define the fillable fields for mass assignment
    protected $fillable = [
        'product_id',
        'quantity',
        'amount',
        'sold_at', // Timestamp for when the sale occurred
        'sale_date', // Date of the sale
        'created_at',
        'updated_at',
    ];

    // Relationship to the Product model
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carousel extends Model
{
    use HasFactory;

    // Define the table associated with the model (optional, if it follows Laravel's naming convention)
    protected $table = 'carousels';

    // Define the fillable attributes (columns you can mass assign)
    protected $fillable = [
        'image_path',  // The path to the image
        'caption',     // The caption for the carousel item
    ];

    // If you want to work with timestamps (created_at, updated_at), ensure the following is true:
    // If your table does not have timestamps, set 'timestamps' to false:
    public $timestamps = true;  // Set to false if your table doesn't have created_at/updated_at
}

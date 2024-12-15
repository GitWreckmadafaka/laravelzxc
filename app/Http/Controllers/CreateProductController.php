<?php
// app/Http/Controllers/CreateProductController.php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CreateProductController extends Controller
{
    public function create()
    {
        return view('users.createproduct');
    }
}

<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;

use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    
    public function add(Product $product)
    {
       
        if (Auth::check()) {
            $user = Auth::user();

         
            /** @var \App\Models\User $user */if (!$user->wishlist()->where('product_id', $product->id)->exists()) {
                $user->wishlist()->attach($product->id);
                return redirect()->back()->with('success', 'Product added to wishlist!');
            } else {
                return redirect()->back()->with('info', 'Product is already in your wishlist!');
            }
        }

      
        return redirect()->route('login')->with('error', 'You must be logged in to add items to your wishlist.');
    }


    public function remove(Product $product)
    {
        
        if (Auth::check()) {
            $user = Auth::user();

           /** @var \App\Models\User $user */  $user->wishlist()->detach($product->id);

            return redirect()->back()->with('success', 'Product removed from wishlist!');
        }

        return redirect()->route('login')->with('error', 'You must be logged in to remove items from your wishlist.');
    }

  
    public function showWishlist()
    {
       
        $userId = auth()->id();

        $wishlist = DB::table('wishlist_product')
            ->join('products', 'wishlist_product.product_id', '=', 'products.id')
            ->where('wishlist_product.user_id', $userId)
            ->select('products.*')
            ->get();


        return view('wishlist.index', compact('wishlist'));
    }
}

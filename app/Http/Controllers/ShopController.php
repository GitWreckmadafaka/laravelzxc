<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Color;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Carousel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShopController extends Controller
{
    public function index()
    {
        // Fetch products along with related colors, brands, and stock information
        $products = Product::with(['color', 'brand'])->get();

        // Fetch additional data
        $colors = Color::all();
        $brands = Brand::all();
        $categories = Category::all();

        // Fetch carousel data
        $carousels = Carousel::all(); // Fetch all carousel items

        // Fetch trending categories
        $topCategories = Category::select('categories.id', 'categories.name', DB::raw('SUM(order_items.quantity) as total_sales'))
        ->join('products', 'products.category_id', '=', 'categories.id') // Join categories with products
        ->join('order_items', 'order_items.product_name', '=', 'products.name') // Join products with order_items on product name
        ->groupBy('categories.id', 'categories.name') // Group by category to get total sales
        ->havingRaw('SUM(order_items.quantity) > 0') // Only include categories with sales
        ->orderByDesc(DB::raw('SUM(order_items.quantity)')) // Order categories by total sales in descending order
        ->take(3) // Get top 3 categories based on total sales
        ->get();

        // Fetch top featured products with the stock added
        $topFeaturedProducts = Product::with(['category', 'color', 'brand'])
        ->join('order_items', 'order_items.product_name', '=', 'products.name')
        ->select('products.*', DB::raw('SUM(order_items.quantity) as total_sales'))
        ->groupBy('products.id', 'products.name', 'products.description', 'products.price', 'products.image_url', 'products.created_at', 'products.updated_at', 'products.color_id', 'products.brand_id', 'products.category_id', 'products.stock') // Add 'products.stock' to group by
        ->orderByDesc('total_sales') // Order by total sales in descending order
        ->limit(3) // Get top 3 products
        ->get();

        // Now, for each top category, load all the products with stock
        $topCategories->map(function ($category) {
            // Load all products for this category with stock
            $category->products = Product::where('category_id', $category->id)->get();
            return $category;
        });

        return view('home', compact('products', 'colors', 'brands', 'categories', 'carousels', 'topCategories', 'topFeaturedProducts'));
    }
}

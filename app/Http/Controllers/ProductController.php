<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\Brand;
use App\Models\Color;
use App\Models\Category;
use App\Models\Order;

class ProductController extends Controller
{



    public function showUsersAndProducts()
    {
        $users = User::all();
        $products = Product::all();
        $categories = Category::all();
        $orders = Order::all();

        return view('users.index', compact('users', 'products', 'categories', 'orders'));
    }

    public function index()
    {
        $products = Product::all();
        $users = User::all();
        $brands = Brand::all();
        $colors = Color::all();


        foreach ($products as $product) {
            if ($product->image_url) {
                Log::info('Image data length for product ID ' . $product->id . ': ' . strlen($product->image_url));
            } else {
                Log::warning('No image data for product ID ' . $product->id);
            }
        }

        return view('users.index', compact('products', 'users', 'brands', 'colors'));


    }

    public function show(Product $product)
    {
        return view('products.productshow', compact('product'));
    }


    public function create()
    {

        $brands = Brand::all();
        $colors = Color::all();
        $categories = Category::all();


        return view('users.createproduct', compact('brands', 'colors', 'categories'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|integer|exists:categories,id',
            'brand' => 'required|integer',
            'color' => 'required|integer',
            'price' => 'required|numeric',
            'image' => 'required|image|mimes:jpg,jpeg,png,gif|max:2048',
            'stock' => 'required|integer|min:0',  // Validate stock input
        ]);

        $image = $request->file('image');
        $imagePath = $image->store('products', 'public');
        $imageUrl = $imagePath;

        $product = Product::create([
            'name' => $validatedData['name'],
            'category_id' => $validatedData['category'],
            'brand_id' => $validatedData['brand'],
            'color_id' => $validatedData['color'],
            'price' => $validatedData['price'],
            'description' => $request->input('description'),
            'image_url' => $imageUrl,
            'stock' => $validatedData['stock'],  // Add stock data
        ]);

        // Log the product creation
        DB::table('logs')->insert([
            'admin_id' => auth()->id(),
            'action' => 'Create Product',
            'details' => "Created product: {$product->name} (ID: {$product->id})",
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Product added successfully');
    }



public function destroy($id)
{
    $product = Product::findOrFail($id);
    $productName = $product->name;
    $product->delete();

    // Directly log the product deletion using DB facade
    DB::table('logs')->insert([
        'admin_id' => auth()->id(),
        'action' => 'Delete Product',
        'details' => "Deleted product: {$productName} (ID: {$id})",
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    return redirect()->route('admin.users.index')->with('success', 'Product deleted successfully!');
}



public function edit($id)
{
    $product = Product::findOrFail($id);
    $categories = Category::all();
    $brands = Brand::all();
    $colors = Color::all();
    return view('users.edit', compact('product', 'categories', 'brands', 'colors'));
}

public function update(Request $request, $id)
{
    $product = Product::findOrFail($id);

    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'required|string',
        'price' => 'required|numeric|min:0',
        'category_id' => 'required|exists:categories,id',
        'brand_id' => 'required|exists:brands,id',
        'color_id' => 'required|exists:colors,id',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'stock' => 'required|integer|min:0',  // Validate stock input
    ]);

    // Store the updated data
    $product->update([
        'name' => $validated['name'],
        'description' => $validated['description'],
        'price' => $validated['price'],
        'category_id' => $validated['category_id'],
        'brand_id' => $validated['brand_id'],
        'color_id' => $validated['color_id'],
        'stock' => $validated['stock'],  // Update stock value
    ]);

    // If there is a new image, update it
    if ($request->hasFile('image')) {
        $imagePath = $request->file('image')->store('product_images', 'public');
        $product->image_url = $imagePath;
        $product->save();
    }

    // Log the product update
    DB::table('logs')->insert([
        'admin_id' => auth()->id(),
        'action' => 'Update Product',
        'details' => "Updated product: {$product->name} (ID: {$product->id})",
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    return redirect()->route('admin.users.index')->with('success', 'Product updated successfully!');
}


public function getBrandsByCategory($categoryId)
{
    // Fetch brands that belong to the selected category
    $brands = Brand::where('category_id', $categoryId)->get();  // Change if using many-to-many

    return response()->json(['brands' => $brands]);
}


}

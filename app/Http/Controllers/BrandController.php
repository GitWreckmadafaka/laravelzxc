<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category; // Import the Category model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BrandController extends Controller
{
    public function create()
    {
        // Fetch all categories for the dropdown
        $categories = Category::all();
        // Fetch all brands for the brand dropdown
        $brands = Brand::all();

        return view('categories.createbrand', compact('categories', 'brands')); // Pass both categories and brands
    }


    public function store(Request $request)
    {
        // Validate the brand name and category selection
        $request->validate([
            'name' => 'required|string|max:255|unique:brands,name',
            'category_id' => 'required|exists:categories,id', // Validate category selection
        ]);

        // Create the brand with the selected category
        $brand = Brand::create([
            'name' => $request->input('name'),
            'category_id' => $request->input('category_id'), // Store the selected category_id
        ]);

        // Log the brand creation action
        DB::table('logs')->insert([
            'admin_id' => auth()->id(),
            'action' => 'Create Brand',
            'details' => "Created brand: {$brand->name} (ID: {$brand->id}) with Category ID: {$brand->category_id}",
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.brands.create')->with('success', 'Brand added successfully');
    }

    public function edit($id)
    {
        // Fetch the brand along with its associated category
        $brand = Brand::findOrFail($id);
        $categories = Category::all(); // Get all categories for the category dropdown
        return view('categories.editbrand', compact('brand', 'categories')); // Pass brand and categories to the view
    }

    public function update(Request $request, $id)
    {
        // Validate the brand name and category selection
        $request->validate([
            'name' => 'required|string|max:255|unique:brands,name,' . $id,
            'category_id' => 'required|exists:categories,id', // Validate category selection
        ]);

        $brand = Brand::findOrFail($id);
        $oldName = $brand->name;
        $oldCategoryId = $brand->category_id;

        // Update the brand and its category
        $brand->update([
            'name' => $request->input('name'),
            'category_id' => $request->input('category_id'), // Update the category_id
        ]);

        // Log the brand update action
        DB::table('logs')->insert([
            'admin_id' => auth()->id(),
            'action' => 'Update Brand',
            'details' => "Updated brand: {$oldName} (ID: {$brand->id}) from Category ID: {$oldCategoryId} to Category ID: {$brand->category_id}",
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.brands.create')->with('success', 'Brand updated successfully');
    }

    public function destroy($id)
    {
        $brand = Brand::findOrFail($id);
        $brandName = $brand->name;

        $brand->products()->delete();
        $brand->delete();

        // Log the brand deletion action
        DB::table('logs')->insert([
            'admin_id' => auth()->id(),
            'action' => 'Delete Brand',
            'details' => "Deleted brand: {$brandName} (ID: {$id}) and its associated products",
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.brands.create')->with('success', 'Brand and associated products deleted successfully');
    }
}

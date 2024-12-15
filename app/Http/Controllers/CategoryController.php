<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    public function create()
    {
        $categories = Category::all();
        return view('categories.createcategory', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
        ]);

        $category = Category::create([
            'name' => $request->input('name'),
        ]);


        DB::table('logs')->insert([
            'admin_id' => auth()->id(),
            'action' => 'Create Category',
            'details' => "Created category: {$category->name} (ID: {$category->id})",
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.categories.create')->with('success', 'Category added successfully');
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return response()->json($category);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $id,
        ]);

        $category = Category::findOrFail($id);
        $oldName = $category->name;
        $category->update([
            'name' => $request->input('name'),
        ]);

        DB::table('logs')->insert([
            'admin_id' => auth()->id(),
            'action' => 'Update Category',
            'details' => "Updated category: {$oldName} (ID: {$category->id}) to {$category->name}",
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.categories.create')->with('success', 'Category updated successfully');
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $categoryName = $category->name;

        DB::table('logs')->insert([
            'admin_id' => auth()->id(),
            'action' => 'Delete Category',
            'details' => "Deleted category: {$categoryName} (ID: {$id})",
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $category->delete();

        return redirect()->route('admin.categories.create')->with('success', 'Category deleted successfully');
    }

    public function getTrendingCategories()
    {
        $trendingCategories = Category::with(['products.orderItems' => function ($query) {
            $query->select('product_name', DB::raw('SUM(quantity) as total_quantity'))
                ->groupBy('product_name');
        }])
        ->withCount(['products as total_purchases' => function ($query) {
            $query->join('order_items', 'products.name', '=', 'order_items.product_name')
                ->select(DB::raw('SUM(order_items.quantity) as total_sales'));
        }])
        ->orderByDesc('total_purchases')
        ->limit(3)
        ->get();

        return response()->json($trendingCategories);
    }
}

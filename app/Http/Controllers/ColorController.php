<?php

namespace App\Http\Controllers;

use App\Models\Color;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ColorController extends Controller
{
    public function create()
    {
        $colors = Color::all();
        return view('categories.createcolor', compact('colors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:colors,name',
        ]);

        $color = Color::create([
            'name' => $request->input('name'),
        ]);

        // Log the color creation
        DB::table('logs')->insert([
            'admin_id' => auth()->id(),
            'action' => 'Create Color',
            'details' => "Created color: {$color->name} (ID: {$color->id})",
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.colors.create')->with('success', 'Color added successfully');
    }

    public function edit($id)
    {
        $color = Color::findOrFail($id);
        return response()->json($color);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:colors,name,' . $id,
        ]);

        $color = Color::findOrFail($id);
        $oldName = $color->name; // Store the old name for logging
        $color->update([
            'name' => $request->input('name'),
        ]);

        // Log the color update
        DB::table('logs')->insert([
            'admin_id' => auth()->id(),
            'action' => 'Update Color',
            'details' => "Updated color: {$oldName} (ID: {$color->id}) to {$color->name}",
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.colors.create')->with('success', 'Color updated successfully');
    }

    public function destroy($id)
    {
        $color = Color::findOrFail($id);
        $colorName = $color->name;

        // Log the color deletion
        DB::table('logs')->insert([
            'admin_id' => auth()->id(),
            'action' => 'Delete Color',
            'details' => "Deleted color: {$colorName} (ID: {$id})",
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Delete the color
        $color->delete();

        return redirect()->route('admin.colors.create')->with('success', 'Color deleted successfully');
    }
}

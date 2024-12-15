<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Carousel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class CarouselController extends Controller
{
    public function index()
    {
        $carousels = Carousel::all();
        return view('carousels.index', compact('carousels'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image',
            'caption' => 'nullable|string|max:255',
        ]);

        $path = $request->file('image')->store('carousels', 'public');

        $carousel = Carousel::create([
            'image_path' => $path,
            'caption' => $request->caption,
        ]);

        DB::table('logs')->insert([
            'admin_id' => auth()->id(),
            'action' => 'Create Carousel Item',
            'details' => "Created carousel item with image: {$carousel->image_path}",
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Carousel item added!');
    }

    public function destroy($id)
    {
        $carousel = Carousel::findOrFail($id);

        if (Storage::disk('public')->exists($carousel->image_path)) {
            Storage::disk('public')->delete($carousel->image_path);
        }

        $carouselName = $carousel->image_path;
        $carousel->delete();

        DB::table('logs')->insert([
            'admin_id' => auth()->id(),
            'action' => 'Delete Carousel Item',
            'details' => "Deleted carousel item with image: {$carouselName}",
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Carousel item deleted!');
    }
}

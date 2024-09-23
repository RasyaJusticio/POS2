<?php

namespace App\Http\Controllers;

use App\Models\Item; // Assuming you have a model named Item
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    // Display a listing of the items.
    public function index(Request $request)
    {
        $per = $request->per ?? 10;
        $page = $request->page ? $request->page - 1 : 0;

        DB::statement('set @no=0+' . $page * $per);
        $data = Item::when($request->search, function (Builder $query, string $search) {
            $query->where('name', 'like', "%$search%");
        })->latest()->paginate($per, ['*', DB::raw('@no := @no + 1 AS no')]);

        return response()->json($data);
    }

    // Show the form for creating a new item.
    public function create()
    {
        return view('items.create');
    }

    // Store a newly created item in the database.
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'quantity' => 'required|integer',
            'description' => 'nullable',
            'image' => 'nullable|image|mimes:jpeg, png, jpg, gif|max:2048',
        ]);

        // Simpan gambar jika ada
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images', 'public'); // Simpan gambar di folder public/images
        }

        // Buat item baru
        Item::create(array_merge($request->all(), ['image_url' => $imagePath]));
        return redirect()->route('items.index')->with('success', 'Item created successfully.');
    }

    // Show the form for editing an existing item.
    public function edit(Item $item)
    {
        return view('items.edit', compact('item'));
    }

    // Update the specified item in the database.
    public function update(Request $request, Item $item)
    {
        $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'quantity' => 'required|integer',
            'description' => 'nullable',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Tambahkan validasi untuk gambar
        ]);

        // Simpan gambar jika ada
        $imagePath = $item->image_url; // Simpan path gambar lama
        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($imagePath) {
                Storage::disk('public')->delete($imagePath);
            }
            // Simpan gambar baru
            $imagePath = $request->file('image')->store('images', 'public');
        }

        // Perbarui item
        $item->update(array_merge($request->all(), ['image_url' => $imagePath]));
        return redirect()->route('items.index')->with('success', 'Item updated successfully.');
    }


    // Remove the specified item from the database.
    public function destroy(Item $item)
    {
        $item->delete();
        return redirect()->route('items.index')->with('success', 'Item deleted successfully.');
    }
}

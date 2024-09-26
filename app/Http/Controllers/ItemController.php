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
    // Validasi data
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'price' => 'required|numeric|min:0',
        'quantity' => 'required|integer|min:0',
        'description' => 'nullable|string',
        'image' => 'nullable|image|max:2048',
    ]);

    // Simpan item
    $item = Item::create($validated);

    return response()->json([
        'message' => 'Item berhasil disimpan!',
        'item' => $item,
    ]);
}


    // Show the form for editing an existing item.
    public function edit(Item $item)
    {
        return view('items.edit', compact('item'));
    }

    // Update the specified item in the database.
    public function update(Request $request, Item $item)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'price' => 'required|numeric|min:0',
        'quantity' => 'required|integer|min:0',
        'description' => 'nullable|string',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    // Proses gambar jika ada
    if ($request->hasFile('image')) {
        if ($item->image_url) {
            Storage::disk('public')->delete($item->image_url); // Hapus gambar lama
        }
        $validated['image_url'] = $request->file('image')->store('images', 'public'); // Simpan gambar baru
    }

    // Perbarui item dengan data yang telah divalidasi
    $item->update($validated);

    return response()->json([
        'message' => 'Item berhasil diperbarui!',
        'item' => $item,
    ]);
}



    // Remove the specified item from the database.
    public function destroy(Item $item)
{
    if ($item->image_url) {
        Storage::disk('public')->delete($item->image_url); // Hapus gambar
    }
    $item->delete();

    return response()->json([
        'message' => 'Item berhasil dihapus!'
    ]);
}


    
}

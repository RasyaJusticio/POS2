<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $per = $request->per ?? 10;
        $page = $request->page ? $request->page - 1 : 0;

        $data = Product::paginate($per);

        return response()->json($data);
    }

    public function store(StoreProductRequest $request)
    {
        $validatedData = $request->validated();

        // Cek jika ada file gambar dan simpan
        if ($request->hasFile('image_url')) {
            $path = $request->file('image_url')->store('produk', 'public');
            $validatedData['image_url'] = Storage::url($path);
        }

        // Simpan produk
        $product = Product::create($validatedData);

        return response()->json([
            'message' => 'Produk berhasil ditambahkan',
            'product' => $product,
        ], 201);
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);

        return response()->json(['produk' => $product]);
    }

    public function update(UpdateProductRequest $request, $id)
    {
        $product = Product::findOrFail($id);
        $validatedData = $request->validated();

        // Cek jika ada file gambar baru dan ganti
        if ($request->hasFile('image_url')) {
            // Hapus gambar lama jika ada
            if ($product->image_url) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $product->image_url));
            }

            // Simpan gambar baru
            $path = $request->file('image_url')->store('produk', 'public');
            $validatedData['image_url'] = Storage::url($path);
        }

        // Update produk
        $product->update($validatedData);

        return response()->json([
            'message' => 'Produk berhasil diperbarui',
            'product' => $product,
        ]);
    }   

    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        // Hapus gambar jika ada
        if ($product->image_url) {
            Storage::disk('public')->delete(str_replace('/storage/', '', $product->image_url));
        }

        // Hapus produk
        $product->delete();

        return response()->json(['message' => 'Produk berhasil dihapus']);
    }
}

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

        // Periksa jika ada file gambar
        if ($request->hasFile('image_url')) {
            $validatedData['image_url'] = $request->file('image_url')->store('produk', 'public');
        }

        $product = Product::create($validatedData);

        return response()->json([
            'success' => true,
            'product' => $product
        ]);
    }

    public function show(Product $product)
    {
        return response()->json([
            'success' => true,
            'produk' => $product,
        ]);
    }


    public function update(UpdateProductRequest $request, Product $product)
    {
        $validated = $request->validated();

        // Periksa jika ada file gambar baru
        if ($request->hasFile('image_url')) {
            // Hapus gambar lama jika ada
            if ($product->image_url) {
                Storage::disk('public')->delete($product->image_url);
            }
            // Simpan gambar baru
            $validated['image_url'] = $request->file('image_url')->store('produk', 'public');
        }

        $product->update($validated);

        return response()->json([
            'success' => true,
            'produk' => $product,
        ]);
    }

    public function destroy(Product $product)
    {
        // Hapus gambar jika ada
        if ($product->image_url) {
            Storage::disk('public')->delete($product->image_url);
        }

        $product->delete();

        return response()->json(['success' => true ]);
    }
}

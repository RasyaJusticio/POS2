<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Exports\ProductExport;
use Maatwebsite\Excel\Facades\Excel;

class ProductController extends Controller
{
      public function print(Request $request)
    {
        // Validate request parameters for printing, if any
        $validator = Validator::make($request->all(), [
            'search' => 'string|nullable',
            'category' => 'string|nullable',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Invalid input data',
                'errors' => $validator->errors()
            ], 422);
        }

        $validated = $validator->validated();
        $search = $validated['search'] ?? null;
        $category = $validated['category'] ?? null;

        // Query products based on search and category filters
        $query = Product::query()
            ->when($search, function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
            })
            ->when($category, function ($q) use ($category) {
                $q->where('category', $category);
            });

        $products = $query->get();

        // Format data for printing (could be HTML, JSON, etc.)
        $html = view('products.print', compact('products'))->render();

        // Return the view or print PDF as needed
        return response()->json([
            'message' => 'Products retrieved successfully for printing.',
            'data' => $html,
        ]);
    }
    public function exportExcel()
    {
        return Excel::download(new ProductExport, 'produk.xlsx');
    }

    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'per' => 'integer|nullable',
            'page' => 'integer|nullable',
            'category' => 'string|nullable',
            'search' => 'string|nullable'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Salah input data',
                'errors' => $validator->errrors()
            ], 422);
        }

        $validated = $validator->validated();

        $category = $validated['category'] ?? null;
        $per = $validated['per'] ?? null;
        $page = $validated['page'] ?? null;
        $search = $validated['search'] ?? null;

        // Query produk
        $query = Product::query()
        ->when($search, function ($q) use ($search) {
            $q->where('name', 'like', '%' . $search . '%')
              ->orWhere('description', 'like', '%' . $search . '%');
        });
        
        if ($per && $page) {
            $result = $query->paginate($per, ['*'], 'page', $page);
        } else {
            $result = $query->get();
        }

        return response()->json($result);
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

    public function toggleSoldOut($id)
    {
        $product = Product::findOrFail($id);
        $product->is_sold_out = !$product->is_sold_out; // Toggle the sold out status
        $product->save();

        return response()->json(['message' => 'Product sold out status updated successfully.', 'product' => $product]);
    }

}
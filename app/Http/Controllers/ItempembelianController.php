<?php

namespace App\Http\Controllers;

use App\Models\Itempembelian;
use App\Models\Product;
use Illuminate\Http\Request;

class ItempembelianController extends Controller
{
    /**
     * Menangani pengiriman Itempembelian.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function submit(Request $request)
    {
        // Validasi data yang diterima dari request
        $validated = $request->validate([
            'products_id' => 'required|exists:products,id',
            'total_price' => 'required|integer|min:1',
            'pembelian_id' => 'required|exists:pembelians,id',
        ]);

        try {
            // Membuat record Itempembelian baru
            $itempembelian = Itempembelian::create([
                'products_id' => $validated['products_id'],
                'total_price' => $validated['total_price'],
                'pembelian_id' => $validated['pembelian_id'],
            ]);

            // Mengembalikan respons sukses
            return response()->json([
                'success' => true,
                'message' => 'Item pembelian berhasil dibuat.',
                'data' => $itempembelian,
            ], 201);

        } catch (\Exception $e) {
            // Menangani error jika terjadi
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat item pembelian: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Mengambil semua produk untuk ditampilkan di form.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getProducts()
    {
        $products = Product::all();

        return response()->json([
            'success' => true,
            'data' => $products,
        ]);
    }
}

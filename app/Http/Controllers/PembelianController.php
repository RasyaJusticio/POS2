<?php

namespace App\Http\Controllers;

use App\Models\Pembelian;
use App\Models\Product;
use Illuminate\Http\Request;

class PembelianController extends Controller
{
    public function store(Request $request)
    {
        // Validasi data
        $validate = $request->validate([
            'user_id' => 'nullable',
            'total_price' => 'required',
            'products_id' => 'required|array', // Validasi produk ID
            'products_id.*' => 'exists:products,id', // Validasi setiap ID produk
        ]);

        // Buat pembelian baru
        $pembelian = Pembelian::create($validate); // Status awal di sini
        $pembelian->items()->syncWithoutDetaching($request->products_id); // Menyinkronkan produk

        // Konfigurasi Midtrans
        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = false; // Ubah ke true jika di produksi
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        // Siapkan parameter transaksi untuk Midtrans
        $params = [
            'transaction_details' => [
                'order_id' => $pembelian->id, // Menggunakan UUID sebagai order_id
                'gross_amount' => $request->total_price,
            ],
        ];

        try {
            // Dapatkan Snap Token dari Midtrans
            $snapToken = \Midtrans\Snap::getSnapToken($params);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal mendapatkan token pembayaran.'], 500);
        }

        return response()->json([
            'success' => true,
            'Pembelian' => $pembelian,
            'payment_url' => $snapToken,
        ]);
    }

    // Fungsi callback untuk menangani notifikasi dari Midtrans
    public function callback(Request $request)
    {
        $notification = $request->all();

        // Contoh bagaimana menangani notifikasi status pembayaran
        $transactionStatus = $notification['transaction_status'];
        $orderId = $notification['order_id'];

        // Cari pembelian berdasarkan order_id (UUID)
        $pembelian = Pembelian::where('id', $orderId)->first();

        if (!$pembelian) {
            return response()->json(['message' => 'Pembelian tidak ditemukan'], 404);
        }

        // Perbarui status berdasarkan status transaksi dari Midtrans
        if ($transactionStatus === 'capture' || $transactionStatus === 'settlement') {
            $pembelian->status = 'Paid';
        } elseif ($transactionStatus === 'pending') {
            $pembelian->status = 'Unpaid';
        } elseif ($transactionStatus === 'deny' || $transactionStatus === 'expire' || $transactionStatus === 'cancel') {
            $pembelian->status = 'Unpaid';
        }

        // Simpan perubahan status
        $pembelian->save();

        return response()->json(['message' => 'Notifikasi pembayaran telah diproses']);
    }

    public function index(Request $request)
    {
        // Ambil semua pembelian
        $pembelians = Pembelian::paginate(10);

        return response()->json($pembelians);
    }

    public function destroy($id)
    {
        // Cari pembelian berdasarkan ID
        $pembelian = Pembelian::find($id);

        if (!$pembelian) {
            return response()->json(['message' => 'Pembelian tidak ditemukan'], 404);
        }

        // Hapus pembelian
        $pembelian->delete();

        return response()->json(['message' => 'Pembelian berhasil dihapus']);
    }

}

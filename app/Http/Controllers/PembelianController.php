<?php

namespace App\Http\Controllers;

use App\Models\Pembelian;
use App\Models\Product;
use App\Models\TransactionReport;
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
        $pembelian = Pembelian::create($validate);
        $pembelian->items()->syncWithoutDetaching($request->products_id); // Menyinkronkan produk

        // Konfigurasi Midtrans
        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = false; // Ubah ke true jika di produksi
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        // Siapkan parameter transaksi untuk Midtrans
        $params = [
            'transaction_details' => [
                'order_id' => $pembelian->uuid, // Menggunakan UUID sebagai order_id
                'gross_amount' => $request->total_price,
            ],
        ];

        try {
            // Dapatkan Snap Token dari Midtrans
            $snapToken = \Midtrans\Snap::getSnapToken($params);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal mendapatkan token pembayaran.'], 500);
        }

        // Simpan laporan transaksi
        TransactionReport::create([
            'pembelian_id' => $pembelian->id, // Menggunakan UUID untuk referensi pembelian
            'status' => 'pending', // Status awal
            'total_price' => $request->total_price,
        ]);

        return response()->json([
            'success' => true,
            'Pembelian' => $pembelian,
            'payment_url' => $snapToken,
        ]);
    }

    public function updateTransactionStatus(Request $request)
    {
        // Validasi data yang diterima dari Midtrans
        $request->validate([
            'order_id' => 'required',
            'transaction_status' => 'required',
        ]);

        // Temukan pembelian berdasarkan UUID
        $transactionReport = TransactionReport::where('pembelian_id', $request->order_id)->first();

        if ($transactionReport) {
            // Update status transaksi
            $transactionReport->update([
                'status' => $request->transaction_status,
            ]);

            return response()->json(['success' => true, 'message' => 'Status transaksi berhasil diperbarui.']);
        }

        return response()->json(['success' => false, 'message' => 'Transaksi tidak ditemukan.'], 404);
    }

}

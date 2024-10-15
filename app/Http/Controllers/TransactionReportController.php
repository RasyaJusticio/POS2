<?php

namespace App\Http\Controllers;

use App\Models\TransactionReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TransactionReportController extends Controller
{
    public function handleMidtransCallback(Request $request)
    {
        // Ambil data dari callback
        $data = $request->all();

        // Tambahkan logging untuk memeriksa data yang diterima
        Log::info('Midtrans Callback Data: ', $data);

        // Pastikan status pembayaran ada dan valid
        if (empty($data['transaction_status']) || empty($data['order_id'])) {
            return response()->json(['success' => false, 'message' => 'Data tidak lengkap.'], 400);
        }

        $transactionStatus = $data['transaction_status'];
        $orderId = $data['order_id']; // Ambil order_id dari callback

        // Temukan laporan transaksi berdasarkan order_id
        $report = TransactionReport::where('pembelian_id', $orderId)->first();

        if ($report) {
            try {
                // Perbarui status transaksi sesuai dengan status yang diterima dari Midtrans
                $report->status = $transactionStatus;
                $report->save();

                return response()->json(['success' => true, 'message' => 'Status transaksi diperbarui.']);
            } catch (\Exception $e) {
                Log::error('Error updating transaction report: ' . $e->getMessage());
                return response()->json(['success' => false, 'message' => 'Gagal memperbarui status.'], 500);
            }
        } else {
            return response()->json(['success' => false, 'message' => 'Laporan transaksi tidak ditemukan.'], 404);
        }
    }

    public function index(Request $request)
    {
        // Ambil data laporan transaksi dengan pagination jika diperlukan
        $reports = TransactionReport::paginate(10); // Atur jumlah item per halaman sesuai kebutuhan

        return response()->json($reports);
    }
}

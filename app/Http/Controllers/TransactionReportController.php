<?php

namespace App\Http\Controllers;

use App\Models\TransactionReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Builder;
use App\Exports\TransactionReportExport;
use Maatwebsite\Excel\Facades\Excel;

class TransactionReportController extends Controller
{

    public function updateStatus(Request $request, $id)
{
    // Validasi data yang diterima
    $request->validate([
        'created' => 'required|boolean', // Pastikan data yang diterima valid
    ]);

    // Temukan laporan transaksi berdasarkan ID
    $report = TransactionReport::find($id);

    // Cek apakah laporan ditemukan
    if ($report) {
        try {
            // Update status
            $report->created = $request->created; // Menyimpan status baru
            $report->save(); // Simpan perubahan

            return response()->json(['success' => true, 'message' => 'Status transaksi diperbarui.', 'data' => $report]);
        } catch (\Exception $e) {
            Log::error('Error updating transaction report: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal memperbarui status.'], 500);
        }
    } else {
        return response()->json(['success' => false, 'message' => 'Laporan transaksi tidak ditemukan.'], 404);
    }
}

    public function exportToExcel()
    {
        return Excel::download(new TransactionReportExport, 'laporan_transaksi.xlsx');
    }

    // Fungsi untuk mencetak laporan transaksi
    public function printTransaction(Request $request)
    {
        $data = TransactionReport::select('id', 'pembelian_id', 'status', 'total_price', 'created_at')
            ->when($request->search, function (Builder $query, string $search) {
                $query->where('pembelian_id', 'like', "%$search%")
                    ->orWhere('status', 'like', "%$search%")
                    ->orWhere('total_price', 'like', "%$search%")
                    ->orWhereDate('created_at', '=', $search); // Atur pencarian tanggal sesuai kebutuhan
            })->get();
    
        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }
    


    public function callback(Request $request)
    {
        // Ambil data dari callback
        $data = $request->all();

        // Tambahkan logging untuk memeriksa data yang diterima dari Midtrans   
        Log::info('Midtrans Callback Data: ', $data);

        // Ambil server key dari konfigurasi (pastikan Anda sudah mengatur server key di .env)
        $serverKey = config('midtrans.server_key');

        // Hitung signature key berdasarkan data yang diterima
        $orderId = $data['order_id'];
        $statusCode = $data['status_code'];
        $grossAmount = $data['gross_amount'];
        $inputSignature = $data['signature_key'];
        $calculatedSignature = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

        // Validasi signature key
        if ($inputSignature !== $calculatedSignature) {
            Log::error('Invalid signature key.');
            return response()->json(['success' => false, 'message' => 'Signature key tidak valid.'], 403);
        }

        // Validasi data yang diterima
        if (empty($data['transaction_status']) || empty($orderId)) {
            return response()->json(['success' => false, 'message' => 'Data tidak lengkap.'], 400);
        }

        $transactionStatus = $data['transaction_status'];  // Status dari Midtrans

        // Temukan laporan transaksi berdasarkan pembelian_id (order_id)
        $report = TransactionReport::where('pembelian_id', $orderId)->first();

        if ($report) {
            try {
                // Perbarui status transaksi berdasarkan status Midtrans
                switch ($transactionStatus) {
                    case 'pending':
                        $report->status = 'pending'; // Pembayaran belum selesai
                        break;
                    case 'settlement':
                        $report->status = 'paid'; // Pembayaran berhasil
                        break;
                    case 'expire':
                        $report->status = 'unpaid'; // Pembayaran kedaluwarsa
                        break;
                    case 'cancel':
                        $report->status = 'unpaid'; // Pembayaran dibatalkan
                        break;
                    case 'deny':
                    case 'failure':
                        $report->status = 'failed'; // Pembayaran gagal atau ditolak
                        break;
                    default:
                        $report->status = $transactionStatus; // Status lain yang tidak terduga
                        break;
                }

                $report->save();
                Log::info("Status transaksi diperbarui untuk order_id: $orderId menjadi $transactionStatus");

                return response()->json(['success' => true, 'message' => 'Status transaksi diperbarui.']);
            } catch (\Exception $e) {
                Log::error('Error updating transaction report: ' . $e->getMessage());
                return response()->json(['success' => false, 'message' => 'Gagal memperbarui status.'], 500);
            }
        } else {
            return response()->json(['success' => false, 'message' => 'Laporan transaksi tidak ditemukan.'], 404);
        }
    }
}

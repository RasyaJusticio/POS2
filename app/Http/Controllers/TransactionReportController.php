<?php

namespace App\Http\Controllers;

use App\Models\TransactionReport;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;
use App\Exports\TransactionReportExport;
use Maatwebsite\Excel\Facades\Excel;

class TransactionReportController extends Controller
{
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
    


    public function handleMidtransCallback(Request $request)
    {
        // Ambil data dari callback
        $data = $request->all();

        // Tambahkan logging untuk memeriksa data yang diterima
        Log::info('Midtrans Callback Data: ', $data);

        // Validasi data yang diterima
        if (empty($data['transaction_status']) || empty($data['order_id'])) {
            return response()->json(['success' => false, 'message' => 'Data tidak lengkap.'], 400);
        }

        $transactionStatus = $data['transaction_status'];
        $orderId = $data['order_id']; // Ambil order_id dari callback

        // Temukan laporan transaksi berdasarkan pembelian_id
        $report = TransactionReport::where('pembelian_id', $orderId)->first();

        if ($report) {
            try {
                // Perbarui status transaksi jika status baru berbeda
                if ($report->status !== $transactionStatus) {
                    $report->status = $transactionStatus;
                    $report->save();
                    Log::info("Status transaksi diperbarui untuk order_id: $orderId menjadi $transactionStatus");
                }
                
                return response()->json(['success' => true, 'message' => 'Status transaksi diperbarui.']);
            } catch (\Exception $e) {
                Log::error('Error updating transaction report: ' . $e->getMessage());
                return response()->json(['success' => false, 'message' => 'Gagal memperbarui status.'], 500);
            }
        } else {
            return response()->json(['success' => false, 'message' => 'Laporan transaksi tidak ditemukan.'], 404);
        }
    }

    public function getTransactionStatus($orderId)
    {
        // Inisialisasi klien Guzzle
        $client = new Client();
        $server_key = config('midtrans.server_key'); // Ambil server key dari konfigurasi

        try {
            $response = $client->request('GET', "https://api.sandbox.midtrans.com/v2/$orderId/status", [
                'headers' => [
                    'Authorization' => 'Basic ' . base64_encode($server_key . ':'),
                    'Accept' => 'application/json',
                ],
            ]);

            // Mengambil isi respons
            $responseBody = json_decode($response->getBody(), true);
            
            return response()->json($responseBody);
        } catch (RequestException $e) {
            // Tangani kesalahan jika ada
            Log::error('Error fetching transaction status: ' . $e->getMessage());
            return response()->json(['error' => 'Gagal mengambil status transaksi.'], 500);
        }
    }

    public function index(Request $request)
    {
        // Ambil data laporan transaksi dengan pagination jika diperlukan
        $reports = TransactionReport::paginate(10); // Atur jumlah item per halaman sesuai kebutuhan

        return response()->json($reports);
    }

    public function destroy($id)
    {
        // Temukan laporan transaksi berdasarkan ID
        $report = TransactionReport::find($id);

        // Cek apakah laporan ditemukan
        if ($report) {
            try {
                // Hapus laporan transaksi
                $report->delete();
                Log::info("Laporan transaksi dengan ID $id telah dihapus.");

                return response()->json(['message' => 'Laporan transaksi berhasil dihapus.']);
            } catch (\Exception $e) {
                Log::error('Error deleting transaction report: ' . $e->getMessage());
                return response()->json(['success' => false, 'message' => 'Gagal menghapus laporan transaksi.'], 500);
            }
        } else {
            return response()->json(['success' => false, 'message' => 'Laporan transaksi tidak ditemukan.'], 404);
        }
    }
}

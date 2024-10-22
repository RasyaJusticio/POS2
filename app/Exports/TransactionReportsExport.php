<?php

namespace App\Exports;

use App\Models\TransactionReport;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;


class TransactionReportsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return TransactionReport::all(); // Ambil semua data transaksi
    }

    public function headings(): array
    {
        return [
            'ID',
            'ID Pembelian',
            'Status Pembayaran',
            'Total',
            'Tanggal Pesanan',
        ];
    }
}

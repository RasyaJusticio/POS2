<?php

namespace App\Exports;

use App\Models\Pembelian;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Carbon\Carbon; // Import Carbon

class TransactionReportExport implements FromCollection, WithHeadings, WithStyles
{   
    /**
     * Mengembalikan koleksi data yang akan diekspor.
     */
    public function collection()
    {
        return Pembelian::select('id', 'uuid', 'customer_name', 'items', 'total_price', 'status', 'created_at')
            ->get()
            ->map(function ($transaction, $key) {
                // Format ID Pembelian menjadi 001, 002, dst
                $formattedId = str_pad($transaction->id, 3, '0', STR_PAD_LEFT);

                // Replace <br /> with newline character
                $items = str_replace("<br />", "\n", nl2br($transaction->items));

                return [
                    'No' => $key + 1,
                    'ID Pembelian' => $formattedId, // Format ID Pembelian dengan 3 digit
                    'Nama' => $transaction->customer_name,
                    'Pesanan' => $items, // Format pesanan agar tampil kebawah
                    'Total' => $transaction->total_price,
                    'Status Pembayaran' => $transaction->status,
                    'Tanggal Pesanan' => $transaction->created_at ? Carbon::parse($transaction->created_at)->translatedFormat('d F Y') : '', // Format date
                    'Pesanan Dibuat' => $transaction->created_at ? 'On Process' : 'Process',
                ];
            });
    }

    /**
     * Mengatur judul kolom di Excel.
     */
    public function headings(): array
    {
        return [
            'No', 'ID Pembelian', 'Nama', 'Pesanan', 'Total', 'Status Pembayaran', 'Tanggal Pesanan', 'Pesanan Dibuat'
        ];
    }

    /**
     * Mengatur gaya untuk tabel.
     */
    public function styles(Worksheet $sheet)
    {
        // Style for header
        $headerStyle = [
            'font' => [
                'bold' => true,
                'size' => 14,
                'name' => 'Calibri',
                'color' => ['argb' => Color::COLOR_WHITE],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER, // Middle align
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FF0070C0'],
            ],
        ];

        $sheet->getStyle('A1:H1')->applyFromArray($headerStyle);

        // Set fixed width for columns (will be auto-adjusted later)
        $sheet->getColumnDimension('A')->setWidth(10);
        $sheet->getColumnDimension('B')->setWidth(15);
        $sheet->getColumnDimension('C')->setWidth(20);
        $sheet->getColumnDimension('D')->setWidth(25);
        $sheet->getColumnDimension('E')->setWidth(15);
        $sheet->getColumnDimension('F')->setWidth(20);
        $sheet->getColumnDimension('G')->setWidth(20);
        $sheet->getColumnDimension('H')->setWidth(25);

        // Set alignment for all data rows
        $sheet->getStyle('A2:H' . (Pembelian::count() + 1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A2:H' . (Pembelian::count() + 1))->getAlignment()->setVertical(Alignment::VERTICAL_CENTER); // Middle align

        // Style for data rows
        $dataStyle = [
            'font' => [
                'size' => 12,
                'name' => 'Calibri',
            ],
        ];

        $sheet->getStyle('A2:H' . (Pembelian::count() + 1))->applyFromArray($dataStyle);

        // Format wrap text for 'Pesanan' column (D)
        $sheet->getStyle('D2:D' . (Pembelian::count() + 1))->getAlignment()->setWrapText(true);

        // Set auto size for columns
        foreach (range('A', 'H') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        // Format 'total_price' column (E) to currency format
        $sheet->getStyle('E2:E' . (Pembelian::count() + 1))
            ->getNumberFormat()->setFormatCode('Rp #,##0');

        // Format 'created_at' column (G) to date format
        // Removed since we are formatting the date in the collection method now

        // Borders for the entire table (header + data)
        $sheet->getStyle('A1:H' . (Pembelian::count() + 1))->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => Color::COLOR_BLACK],
                ],
            ],
        ]);

        return [];
    }
}

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

class TransactionReportExport implements FromCollection, WithHeadings, WithStyles
{   
    /**
     * Mengembalikan koleksi data yang akan diekspor.
     */
    public function collection()
    {
        return Pembelian::select('id', 'uuid', 'items', 'total_price', 'status', 'created_at')->get();
    }

    /**
     * Mengatur judul kolom di Excel.
     */
    public function headings(): array
    {
        return [
            'No', 'ID Pembelian', 'Pesanan', 'Total', 'Status Pembayaran', 'Tanggal Pesanan'
        ];
    }

    /**
     * Mengatur gaya untuk tabel.
     */
    public function styles(Worksheet $sheet)
    {
        // Style untuk header
        $headerStyle = [
            'font' => [
                'bold' => true,
                'size' => 14, // Mengubah ukuran font untuk header
                'name' => 'Calibri', // Mengubah jenis font header dengan huruf "C" besar
                'color' => ['argb' => Color::COLOR_WHITE],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FF0070C0'], // Warna biru untuk header
            ],
        ];

        // Terapkan style ke baris header (baris pertama)
        $sheet->getStyle('A1:F1')->applyFromArray($headerStyle);

        // Set auto width for columns
        foreach (range('A', 'F') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        // Set alignment for all data rows
        $sheet->getStyle('A2:F' . (Pembelian::count() + 1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Style untuk data baris
        $dataStyle = [
            'font' => [
                'size' => 12, // Mengubah ukuran font untuk data
                'name' => 'Calibri', // Mengubah jenis font untuk data
            ],
        ];

        // Terapkan gaya untuk seluruh tabel data (A2 hingga akhir)
        $sheet->getStyle('A2:F' . (Pembelian::count() + 1))->applyFromArray($dataStyle);

        // Format kolom total_price (D) ke format mata uang Rupiah
        $sheet->getStyle('D2:D' . (Pembelian::count() + 1))
            ->getNumberFormat()->setFormatCode('Rp #,##0');

        // Format kolom created_at (F) ke format tanggal
        $sheet->getStyle('F2:F' . (Pembelian::count() + 1))
            ->getNumberFormat()->setFormatCode('DD/MM/YYYY');

        // Border untuk seluruh tabel (header + data)
        $sheet->getStyle('A1:F' . (Pembelian::count() + 1))->applyFromArray([
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

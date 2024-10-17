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
            ['No', 'ID Pembelian', 'Pesanan', 'Total', 'Status Pembayaran', 'Tanggal Pesanan']
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
                'size' => 12,
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

        // Apply style to the header row
        $sheet->getStyle('A1:E1')->applyFromArray($headerStyle);

        // Set auto width for columns
        foreach (range('A', 'E') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        // Set alignment for all data rows
        $sheet->getStyle('A2:E' . (Pembelian::count() + 1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Style for data rows
        $dataStyle = [
            'font' => [
                'size' => 11,
            ],
        ];

        // Apply data style to the whole table
        $sheet->getStyle('A2:E' . (Pembelian::count() + 1))->applyFromArray($dataStyle);

        // Format the total_price column (D) to currency
        $sheet->getStyle('D2:D' . (Pembelian::count() + 1))
            ->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_CURRENCY_USD_SIMPLE);

        // Format the created_at column (E) to date format
        $sheet->getStyle('E2:E' . (Pembelian::count() + 1))
            ->getNumberFormat()->setFormatCode('DD/MM/YYYY');

        // Border for the entire table (header + data)
        $sheet->getStyle('A1:E' . (Pembelian::count() + 1))->applyFromArray([
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

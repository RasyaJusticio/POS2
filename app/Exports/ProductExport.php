<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProductExport implements FromCollection, WithHeadings, WithStyles
{
    public function collection()
    {
        return Product::all(); // Retrieve all product data
    }

    public function headings(): array
    {
        return [
            'No',
            'UID',
            'Name',
            'Category',
            'Price',
            'Description',
            'Stock Status',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Header styling: Bold, centered, with a clean light gray background
        $sheet->getStyle('A1:G1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 12,
                'name' => 'Calibri',
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FFD6D6D6'], // Soft gray for the header
            ],
            'borders' => [
                'bottom' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => 'FFBBBBBB'], // Light border under header
                ],
            ],
        ]);

        // Border for the whole sheet
        $sheet->getStyle('A1:G1000')->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => 'FFDDDDDD'], // Light gray borders
                ],
            ],
        ]);

        // Set alignment and padding for table cells
        $sheet->getStyle('A2:A1000')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER); // 'No' column
        $sheet->getStyle('B2:G1000')->applyFromArray([
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'font' => [
                'size' => 11,
                'name' => 'Calibri',
            ],
        ]);

        // Alternating row color for better readability
        for ($i = 2; $i <= 1000; $i++) {
            if ($i % 2 == 0) {
                $sheet->getStyle('A' . $i . ':G' . $i)->applyFromArray([
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['argb' => 'FFF3F3F3'], // Light gray for even rows
                    ],
                ]);
            }
        }

        // Set a consistent row height for better readability
        $sheet->getDefaultRowDimension()->setRowHeight(20); // Perfect height for modern look

        // Auto-fit columns for content clarity
        foreach (range('A', 'G') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        return [];
    }
}

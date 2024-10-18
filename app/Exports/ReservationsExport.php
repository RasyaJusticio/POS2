<?php

namespace App\Exports;

use App\Models\Reservation;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Font;

class ReservationsExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    public function collection()
    {
        $reservations = Reservation::select('id', 'name', 'phone', 'date', 'start_time', 'end_time', 'guests')->get();
    
        // Menghitung total
        $totalGuests = $reservations->sum('guests');
        $totalReservations = $reservations->count();
    
        // Menambahkan baris total di akhir
        $reservations->push((object) [
            'id' => '',
            'name' => 'Total Reservations',
            'phone' => '',
            'date' => '',
            'start_time' => '',
            'end_time' => '',
            'guests' => $totalReservations,
            'status' => ''
        ]);
    
        $reservations->push((object) [
            'id' => '',
            'name' => 'Total Guests',
            'phone' => '',
            'date' => '',
            'start_time' => '',
            'end_time' => '',
            'guests' => $totalGuests,
            'status' => ''
        ]);
    
        return $reservations;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Name',
            'Phone',
            'Date',
            'Start Time',
            'End Time',
            'Guests',
            'Status',
        ];
    }

    public function map($reservation): array
    {
        return [
            $reservation->id,
            $reservation->name,
            $reservation->phone,
            $reservation->date,
            $reservation->start_time,
            $reservation->end_time,
            $reservation->guests,
            $this->getReservationStatus($reservation),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Gaya untuk header yang lebih modern
        $headerStyle = [
            'font' => [
                'bold' => true,
                'size' => 12,
                'name' => 'Calibri',
                'color' => ['argb' => Color::COLOR_WHITE],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FF2E75B6'], // Warna biru lembut modern
            ],
        ];

        // Terapkan gaya header
        $sheet->getStyle('A1:H1')->applyFromArray($headerStyle);

        // Gaya border untuk semua data
        $sheet->getStyle('A1:H' . ($sheet->getHighestRow()))->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => Color::COLOR_BLACK],
                ],
            ],
        ]);

        // Terapkan gaya untuk baris total
        $highestRow = $sheet->getHighestRow();
        $sheet->getStyle('A' . $highestRow . ':H' . $highestRow)
              ->applyFromArray([
                  'font' => ['bold' => true, 'name' => 'Calibri'],
                  'alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT],
              ]);

        // Gaya untuk data agar lebih modern
        $sheet->getStyle('A1:H' . ($sheet->getHighestRow()))->applyFromArray([
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'font' => [
                'name' => 'Calibri',
                'size' => 11,
                'color' => ['argb' => Color::COLOR_BLACK],
            ],
        ]);

        // Atur lebar kolom secara otomatis
        return [
            'A' => ['width' => 10],
            'B' => ['width' => 30],
            'C' => ['width' => 20],
            'D' => ['width' => 20],
            'E' => ['width' => 15],
            'F' => ['width' => 15],
            'G' => ['width' => 10],
            'H' => ['width' => 20],
        ];
    }

    private function getReservationStatus($reservation)
    {
        $now = new \DateTime();
        $endTime = new \DateTime("{$reservation->date} {$reservation->end_time}");
        return $now > $endTime ? 'Reservation Ended' : 'Active';
    }
}

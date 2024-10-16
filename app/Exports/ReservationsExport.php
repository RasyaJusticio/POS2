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

class ReservationsExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    public function collection()
    {
        return Reservation::select('id', 'name', 'phone', 'date', 'start_time', 'end_time', 'guests')->get();
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
            'Status', // New Status heading
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
            $this->getReservationStatus($reservation), // Get status for each reservation
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Header styles
        $sheet->getStyle('A1:H1')->getFont()->setBold(true);
        $sheet->getStyle('A1:H1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1:H1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
        $sheet->getStyle('A1:H1')->getFill()->getStartColor()->setARGB('FFDDDDDD');

        // Apply borders to all data cells
        $sheet->getStyle('A1:H' . ($sheet->getHighestRow()))->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

        // Set the column widths
        return [
            'A' => ['width' => 10],
            'B' => ['width' => 30],
            'C' => ['width' => 20],
            'D' => ['width' => 20],
            'E' => ['width' => 15],
            'F' => ['width' => 15],
            'G' => ['width' => 10],
            'H' => ['width' => 20], // New width for Status column
        ];
    }

    private function getReservationStatus($reservation)
    {
        $now = new \DateTime();
        $endTime = new \DateTime("{$reservation->date} {$reservation->end_time}");
        return $now > $endTime ? 'Reservation Ended' : 'Active';
    }
}

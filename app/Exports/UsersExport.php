<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class UsersExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    public function collection()
    {
        return User::select('name', 'address', 'email', 'phone')->get(); // Only include fields displayed in your form
    }

    public function headings(): array
    {
        return [
            'Nama',   // Heading for Name
            'Alamat', // Heading for Address
            'Email',  // Heading for Email
            'Nomor Telepon', // Heading for Phone
        ];
    }

    public function map($user): array
    {
        return [
            $user->name,
            $user->address,
            $user->email,
            $user->phone,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Header styles
        $sheet->getStyle('A1:D1')->getFont()->setBold(true); // Bold the header
        $sheet->getStyle('A1:D1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER); // Center align headers

        // Set the background color for headers
        $sheet->getStyle('A1:D1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
        $sheet->getStyle('A1:D1')->getFill()->getStartColor()->setARGB('FFDDDDDD'); // Light gray color

        // Apply borders to all data cells
        $sheet->getStyle('A1:D' . ($sheet->getHighestRow()))->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

        // Set the column widths
        return [
            'A' => ['width' => 30],
            'B' => ['width' => 30],
            'C' => ['width' => 40],
            'D' => ['width' => 20],
        ];
    }
}

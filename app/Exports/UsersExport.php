<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class UsersExport implements FromCollection, WithHeadings, WithStyles
{
    public function collection()
    {
        return User::all();
    }

    public function headings(): array
    {
        return [
            'ID', 'Name', 'Email', 'Email Verified At', 'Phone', 'OTP Code', 'OTP Expired',
            'Gender', 'Birth Date', 'HCP Index', 'Faculty', 'Batch', 'Office Name', 'Address',
            'Business Sector', 'Position', 'Remember Token', 'Created At', 'Updated At', 'OTP Code Login',
            'Active', 'Phone Verified At', 'Image', 'Flag Done Profile', 'Group ID', 'City ID', 'FCM Token',
            'Flag Community', 'Community ID', 'Player ID', 'Deleted At', 'Is Admin', 'Nickname', 'EULA Flag',
            'Faculty ID', 'Region', 'Birth Place', 'Age', 'Desa/Kelurahan', 'Kecamatan', 'Kota/Kabupaten',
            'Postal Code', 'Provinsi', 'Year of Entry', 'Year of Retirement', 'Retirement Type',
            'Last Employee Status', 'Last Division', 'Spouse Name', 'Shirt Size', 'Notes',
            'Emergency Contact Name', 'EC Kinship', 'Status Anggota', 'Nomor Anggota', 'Reset Request',
            'EULA Accepted', 'EC Contact', 'Pass Away Status', 'Flag Verified'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $lastColumn = 'BH'; 

        $styleArray = [
            'font' => [
                'bold' => true,
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => '4DA3FF',
                ],
            ],
            'alignment' => [
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
        ];

        $sheet->getStyle('A1:' . $lastColumn . '1')->applyFromArray($styleArray);
        $sheet->getRowDimension(1)->setRowHeight(30); 

        return [];
    }

}



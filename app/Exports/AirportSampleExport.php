<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AirportSampleExport implements FromArray, WithHeadings, WithStyles
{
    public function headings(): array
    {
        return ['ST', 'Locid', 'City', 'Airport Name'];
    }

    public function array(): array
    {
        return [
            ['GA', 'ATL', 'Atlanta',      'Hartsfield/Jackson Atlanta International'],
            ['CA', 'LAX', 'Los Angeles',  'Los Angeles International Airport'],
            ['NY', 'JFK', 'New York',     'John F. Kennedy International Airport'],
            ['IL', 'ORD', 'Chicago',      "O'Hare International Airport"],
            ['TX', 'DFW', 'Dallas',       'Dallas/Fort Worth International Airport'],
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}

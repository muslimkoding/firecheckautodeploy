<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class HydrantExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $hydrants;

    public function __construct($hydrants)
    {
        $this->hydrants = $hydrants;
    }

    public function collection()
    {
        return $this->hydrants;
    }

    public function headings(): array
    {
        return [
            'Nomor Hydrant',
            'Lokasi',
            'Deskripsi',
            'QR Code',
            'Status',
            'Zona',
            'Gedung',
            'Lantai',
            'Merek',
            'Tipe Hydrant',
            'Kondisi',
            'Dibuat Oleh',
        ];
    }

    public function map($hydrant): array
    {
        return [
            $hydrant->number_hydrant,
            $hydrant->location,
            $hydrant->description,
            $hydrant->qr_code ?? '-',
            $hydrant->is_active ? 'AKTIF' : 'NON-AKTIF',
            $hydrant->zone->name ?? '-',
            $hydrant->building->name ?? '-',
            $hydrant->floor->name ?? '-',
            $hydrant->brand->name ?? '-',
            $hydrant->hydrantType->name ?? '-',
            $hydrant->extinguisherCondition->name ?? '-',
            $hydrant->user->name ?? '-',

        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'color' => ['argb' => 'FFE6E6FA']
                ]
            ],
        ];
    }
}
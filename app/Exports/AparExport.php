<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AparExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $apars;

    public function __construct($apars)
    {
        $this->apars = $apars;
    }

    public function collection()
    {
        return $this->apars;
    }

    public function headings(): array
    {
        return [
            'Nomor APAR',
            'Lokasi',
            'Berat (kg)',
            'Deskripsi',
            'Tanggal Kadaluarsa',
            'QR Code',
            'Status',
            'Zona',
            'Gedung',
            'Lantai',
            'Merek',
            'Tipe APAR',
            'Kondisi',
            'Dibuat Oleh',
            'Status Kadaluarsa'
        ];
    }

    public function map($apar): array
    {
        $today = now();
        $expiredStatus = '-';
        
        if ($apar->expired_date) {
            $expiredDate = \Carbon\Carbon::parse($apar->expired_date);
            if ($expiredDate->lessThan($today)) {
                $expiredStatus = 'KADALUARSA';
            } elseif ($expiredDate->diffInDays($today) <= 30) {
                $expiredStatus = 'AKAN KADALUARSA';
            } else {
                $expiredStatus = 'AMAN';
            }
        }

        return [
            $apar->number_apar,
            $apar->location,
            $apar->weight_of_extinguiser,
            $apar->description ?? '-',
            $apar->expired_date ? \Carbon\Carbon::parse($apar->expired_date)->format('d/m/Y') : '-',
            $apar->qr_code ?? '-',
            $apar->is_active ? 'AKTIF' : 'NON-AKTIF',
            $apar->zone->name ?? '-',
            $apar->building->name ?? '-',
            $apar->floor->name ?? '-',
            $apar->brand->name ?? '-',
            $apar->aparType->name ?? '-',
            $apar->extinguisherCondition->name ?? '-',
            $apar->user->name ?? '-',
            $expiredStatus
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
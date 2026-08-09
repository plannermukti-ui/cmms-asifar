<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class KpiMasterDataExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $units;
    protected $startDate;
    protected $endDate;

    public function __construct($units, $startDate, $endDate)
    {
        $this->units = $units;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function collection()
    {
        return $this->units;
    }

    public function headings(): array
    {
        return [
            ['KPI MASTER DATA'],
            ['Periode: ' . ($this->startDate ? $this->startDate->format('d/m/Y') : '-') . ' s/d ' . ($this->endDate ? $this->endDate->format('d/m/Y') : '-')],
            [],
            [
                'No',
                'Unit',
                'Tipe',
                'Model',
                'HM Awal',
                'HM Akhir',
                'OP (hrs)',
                'EWH',
                'Event BD',
                'BD (hrs)',
                'STB',
                'PA (%)',
                'MA (%)',
                'MTBF',
                'MTTR',
                'UA (%)',
                'EU (%)'
            ]
        ];
    }

    public function map($unit): array
    {
        static $rowNumber = 0;
        $rowNumber++;

        return [
            $rowNumber,
            $unit->nomor_unit ?? '-',
            $unit->type->name ?? '-',
            $unit->model->name ?? '-',
            number_format($unit->hm_awal, 1),
            number_format($unit->hm_akhir, 1),
            number_format($unit->op_hrs, 1),
            number_format($unit->ewh, 1),
            $unit->event_bd,
            number_format($unit->bd_hrs, 1),
            number_format($unit->stb, 1),
            number_format($unit->pa, 1) . '%',
            number_format($unit->ma, 1) . '%',
            number_format($unit->mtbf, 1),
            number_format($unit->mttr, 1),
            number_format($unit->ua, 1) . '%',
            number_format($unit->eu, 1) . '%'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->mergeCells('A1:Q1');
        $sheet->mergeCells('A2:Q2');
        
        return [
            1    => ['font' => ['bold' => true, 'size' => 14], 'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]],
            2    => ['font' => ['italic' => true], 'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]],
            4    => [
                'font' => ['bold' => true], 
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FFE0E0E0']
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ]
                ]
            ],
        ];
    }
}

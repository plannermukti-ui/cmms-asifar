<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class WorkOrderExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $workOrders;

    public function __construct($workOrders)
    {
        $this->workOrders = $workOrders;
    }

    public function collection()
    {
        return $this->workOrders;
    }

    public function headings(): array
    {
        return [
            ['DATA WORK ORDER'],
            ['Diekspor pada: ' . date('d/m/Y H:i:s')],
            [],
            [
                'No',
                'No WO',
                'Status WO',
                'Tipe WO',
                'Downtime Code',
                'Opportunity',
                'Master Unit',
                'Hour Meter',
                'Lokasi Kerusakan',
                'Waktu BD',
                'Waktu RFU',
                'Durasi BD (Jam)',
                'Problem',
                'Breakdown Type',
                'Component Group',
                'Category 1',
                'Category 2',
                'Category 3',
                'Category 4',
                'Category 5'
            ]
        ];
    }

    public function map($wo): array
    {
        static $rowNumber = 0;
        $rowNumber++;

        // Hitung Durasi BD
        $durasiBd = 0;
        if ($wo->waktu_bd) {
            $waktuBd = Carbon::parse($wo->waktu_bd);
            $waktuRfu = $wo->waktu_rfu ? Carbon::parse($wo->waktu_rfu) : Carbon::now('Asia/Makassar');
            
            if ($waktuRfu > $waktuBd) {
                $durasiBd = $waktuBd->diffInMinutes($waktuRfu) / 60; // dalam jam
            }
        }

        // Ambil problem dari task pertama
        $problem = '-';
        if ($wo->tasks && $wo->tasks->count() > 0) {
            $problem = $wo->tasks->first()->problem;
        }

        return [
            $rowNumber,
            $wo->no_wo ?? '-',
            $wo->status_wo ?? '-',
            $wo->tipe_wo ?? '-',
            $wo->downtime_code ?? '-',
            $wo->opportunity ? 'Yes' : 'No',
            $wo->unit->nomor_unit ?? '-',
            $wo->hours_meter ?? '-',
            $wo->lokasi_kerusakan ?? '-',
            $wo->waktu_bd ? Carbon::parse($wo->waktu_bd)->format('Y-m-d H:i') : '-',
            $wo->waktu_rfu ? Carbon::parse($wo->waktu_rfu)->format('Y-m-d H:i') : '-',
            number_format($durasiBd, 1),
            $problem,
            $wo->breakdownType->name ?? '-',
            $wo->componentGroup->name ?? '-',
            $wo->category1->name ?? '-',
            $wo->category2->name ?? '-',
            $wo->category3->name ?? '-',
            $wo->category4->name ?? '-',
            $wo->category5->name ?? '-'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->mergeCells('A1:T1');
        $sheet->mergeCells('A2:T2');
        
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

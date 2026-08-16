<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class DmbdExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $workOrders;

    public function __construct($workOrders)
    {
        $this->workOrders = $workOrders;
    }

    public function collection()
    {
        $rows = collect();
        $woNumber = 1;

        foreach ($this->workOrders as $wo) {
            $woRows = collect();

            $durasiBd = 0;
            if ($wo->waktu_bd) {
                $waktuBd = Carbon::parse($wo->waktu_bd);
                $waktuRfu = $wo->waktu_rfu ? Carbon::parse($wo->waktu_rfu) : Carbon::now('Asia/Makassar');
                if ($waktuRfu > $waktuBd) {
                    $durasiBd = $waktuBd->diffInMinutes($waktuRfu) / 60;
                }
            }

            $hasTasks = $wo->tasks && $wo->tasks->count() > 0;
            
            if (!$hasTasks) {
                // WO with no tasks
                $woRows->push([
                    'is_first_wo' => true,
                    'no' => $woNumber,
                    'wo' => $wo,
                    'durasi' => $durasiBd,
                    'task_no' => null,
                    'problem' => null,
                    'subtask_no' => null,
                    'action' => null,
                    'status' => null,
                    'mol_pr' => null
                ]);
            } else {
                $tIdx = 1;
                foreach ($wo->tasks as $task) {
                    $hasSubtasks = $task->subtasks && $task->subtasks->count() > 0;
                    
                    if (!$hasSubtasks) {
                        $woRows->push([
                            'is_first_wo' => $woRows->isEmpty(),
                            'no' => $woRows->isEmpty() ? $woNumber : null,
                            'wo' => $wo,
                            'durasi' => $durasiBd,
                            'task_no' => $tIdx,
                            'problem' => $task->problem,
                            'subtask_no' => null,
                            'action' => null,
                            'status' => $task->status,
                            'mol_pr' => null
                        ]);
                    } else {
                        $stIdx = 1;
                        foreach ($task->subtasks as $subtask) {
                            $molPrs = [];
                            if ($subtask->parts) {
                                foreach ($subtask->parts as $part) {
                                    if ($part->mol_pr) {
                                        $molPrs[] = $part->mol_pr;
                                    }
                                }
                            }
                            $molPr = count($molPrs) > 0 ? implode(', ', array_unique($molPrs)) : null;

                            $woRows->push([
                                'is_first_wo' => $woRows->isEmpty(),
                                'no' => $woRows->isEmpty() ? $woNumber : null,
                                'wo' => $wo,
                                'durasi' => $durasiBd,
                                'task_no' => $stIdx === 1 ? $tIdx : null,
                                'problem' => $stIdx === 1 ? $task->problem : null,
                                'subtask_no' => $tIdx . '.' . $stIdx,
                                'action' => $subtask->action,
                                'status' => $subtask->status,
                                'mol_pr' => $molPr
                            ]);
                            $stIdx++;
                        }
                    }
                    $tIdx++;
                }
            }

            foreach ($woRows as $row) {
                $rows->push($row);
            }
            $woNumber++;
        }

        return $rows;
    }

    public function headings(): array
    {
        return [
            ['Diekspor: ' . date('d/m/Y H:i:s')],
            [],
            [],
            [
                'No',
                'No WO',
                'Status WO',
                'Tipe WO',
                'Master Unit',
                'Hour Meter',
                'Lokasi Kerusakan',
                'Waktu BD',
                'Waktu RFU',
                'Durasi BD (Jam)',
                'Downtime Code',
                'Task',
                'Problem',
                'Subtask',
                'Subtask/Action',
                'Status',
                'MOL/PR'
            ]
        ];
    }

    public function map($row): array
    {
        if ($row['is_first_wo']) {
            $wo = $row['wo'];
            return [
                $row['no'],
                $wo->no_wo ?? '-',
                $wo->status_wo ?? '-',
                $wo->tipe_wo ?? '-',
                $wo->unit->nomor_unit ?? '-',
                $wo->hours_meter ?? '-',
                $wo->lokasi_kerusakan ?? '-',
                $wo->waktu_bd ? Carbon::parse($wo->waktu_bd)->format('Y-m-d H:i') : '-',
                $wo->waktu_rfu ? Carbon::parse($wo->waktu_rfu)->format('Y-m-d H:i') : '-',
                number_format($row['durasi'], 1),
                $wo->downtime_code ?? '-',
                $row['task_no'],
                $row['problem'],
                $row['subtask_no'],
                $row['action'],
                $row['status'],
                $row['mol_pr']
            ];
        } else {
            return [
                null,
                null,
                null,
                null,
                null,
                null,
                null,
                null,
                null,
                null,
                null,
                $row['task_no'],
                $row['problem'],
                $row['subtask_no'],
                $row['action'],
                $row['status'],
                $row['mol_pr']
            ];
        }
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->mergeCells('A1:Q1');
        
        return [
            1 => ['font' => ['italic' => true]],
            4 => [
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

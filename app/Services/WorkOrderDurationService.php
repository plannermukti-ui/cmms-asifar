<?php

namespace App\Services;

use App\Models\WorkOrder;
use Carbon\Carbon;

class WorkOrderDurationService
{
    /**
     * Hitung ringkasan durasi & waktu tanggap sebuah Work Order:
     *
     * 1. Respontime        = Date Problem (hanya Task pertama) - Waktu BD
     * 2. Durasi Subtask    = duration_hours tiap subtask (fallback: Date Finish - Date Action).
     *    Aturan rantai     = subtask yang belum memiliki Date Finish memakai Date Finish
     *                        paling akhir dari subtask lain yang sudah terisi (dalam task yang
     *                        sama terlebih dahulu, lalu seluruh WO).
     * 3. Durasi per Tipe Breakdown = pengelompokan durasi subtask berdasarkan breakdown type.
     * 4. Penyeimbangan     = jika Durasi (Hrs) lebih kecil dari total durasi seluruh subtask,
     *                        Date Action tiap subtask dikurangi secara proporsional agar
     *                        perhitungan seimbang.
     * 5. Rekonsiliasi      = Durasi (Hrs) = Respontime + Total Durasi Subtask + No Action.
     *
     * @param  WorkOrder $workOrder (wajib memuat relasi tasks.subtasks + breakdownType)
     * @return array
     */
    public function summarize(WorkOrder $workOrder): array
    {
        $durasiHrs = $workOrder->durasi_hrs !== null ? (float) $workOrder->durasi_hrs : null;

        // ---- 1. Respontime: Date Problem task pertama - Waktu BD ----
        $respontime = null;
        $firstTask = $workOrder->tasks->sortBy('id')->first();
        if ($workOrder->waktu_bd && $firstTask && $firstTask->date_problem) {
            // Di-clamp ke >= 0 agar data lama (date_problem < waktu_bd) tidak menggelembungkan budget subtask.
            $respontime = max(0.0, round($this->hoursBetween($workOrder->waktu_bd, $firstTask->date_problem), 2));
        }

        // ---- 2. Durasi tiap subtask (+ aturan rantai Date Finish) ----
        $latestFinishAcrossWo = $this->latestFinish($workOrder->tasks->flatMap(fn ($t) => $t->subtasks));

        $subtasks = [];
        $taskNo = 0;
        foreach ($workOrder->tasks->sortBy('id') as $task) {
            $taskNo++;
            $latestFinishInTask = $this->latestFinish($task->subtasks);

            foreach ($task->subtasks->sortBy('id') as $st) {
                $duration = ($st->duration_hours !== null && $st->duration_hours !== '')
                    ? (float) $st->duration_hours
                    : null;

                if ($duration === null && $st->date_action && $st->date_finish) {
                    $duration = round($this->hoursBetween($st->date_action, $st->date_finish), 2);
                }

                // Aturan rantai: Date Finish kosong -> pakai Date Finish paling akhir yang ada.
                $effectiveFinish = $st->date_finish ?? $latestFinishInTask ?? $latestFinishAcrossWo;

                $subtasks[] = [
                    'task_no' => $taskNo,
                    'task_problem' => $task->problem,
                    'action' => $st->action,
                    'breakdown_type_id' => $st->breakdown_type_id,
                    'breakdown_type' => $st->breakdownType ? $st->breakdownType->name : null,
                    'breakdown_code' => $st->breakdownType ? $st->breakdownType->code : null,
                    'duration' => $duration !== null ? round($duration, 2) : 0.0,
                    'date_action' => $st->date_action,
                    'effective_finish' => $effectiveFinish,
                    'reduced' => false,
                    'adjusted_duration' => $duration !== null ? round($duration, 2) : 0.0,
                    'adjusted_date_action' => null,
                ];
            }
        }

        $totalSubtask = round(array_sum(array_column($subtasks, 'duration')), 2);

        // ---- 3. Penyeimbangan ----
        // Budget untuk subtask = Durasi (Hrs) - Respontime. Jika total durasi subtask melebihi
        // budget, Date Action tiap subtask dikurangi proporsional agar rekonsiliasi seimbang.
        $available = null;
        if ($durasiHrs !== null) {
            $available = max(0.0, $durasiHrs - ($respontime ?? 0.0));
        }

        $overrun = $available !== null && $totalSubtask > 0 && $totalSubtask > $available;
        $scale = $overrun ? $available / $totalSubtask : 1.0;

        $running = 0.0;
        foreach ($subtasks as &$row) {
            $row['reduced'] = $overrun;
            $row['adjusted_duration'] = $overrun
                ? round($row['duration'] * $scale, 2)
                : $row['duration'];
            $running += $row['adjusted_duration'];
        }
        unset($row);

        $adjustedTotal = $overrun ? round($totalSubtask * $scale, 2) : $totalSubtask;

        // Koreksi selisih pembulatan per-subtask pada baris terakhir yang berdurasi > 0
        // agar jumlah baris persis sama dengan total (tidak ada selisih 0.01).
        $diff = round($adjustedTotal - $running, 2);
        if (count($subtasks) > 0 && abs($diff) > 0.0001) {
            for ($i = count($subtasks) - 1; $i >= 0; $i--) {
                if ($subtasks[$i]['adjusted_duration'] > 0) {
                    $subtasks[$i]['adjusted_duration'] = round($subtasks[$i]['adjusted_duration'] + $diff, 2);
                    break;
                }
            }
        }

        foreach ($subtasks as &$row) {
            if ($row['effective_finish']) {
                $row['adjusted_date_action'] = $row['effective_finish']->copy()->subHours($row['adjusted_duration']);
            }
        }
        unset($row);

        // ---- 4. No Action & Rekonsiliasi ----
        $noAction = null;
        if ($durasiHrs !== null) {
            $noAction = round(max(0.0, $durasiHrs - ($respontime ?? 0.0) - $adjustedTotal), 2);
        }

        $warnings = [];
        if ($overrun) {
            $warnings[] = sprintf(
                'Durasi (Hrs) (%s) lebih kecil dari total durasi seluruh subtask (%s). ' .
                'Date Action setiap subtask dikurangi proporsional menjadi total %s Hrs agar perhitungan seimbang.',
                number_format($durasiHrs, 2),
                number_format($totalSubtask, 2),
                number_format($adjustedTotal, 2)
            );
        }

        // ---- 5. Kelompokkan durasi per Tipe Breakdown ----
        $byType = [];
        foreach ($subtasks as $row) {
            $key = $row['breakdown_type_id'] ?? $row['breakdown_type'] ?? 'tanpa-tipe';
            $label = $row['breakdown_type']
                ? trim(($row['breakdown_code'] ? $row['breakdown_code'] . ' - ' : '') . $row['breakdown_type'])
                : 'Tanpa Tipe Breakdown';

            if (!isset($byType[$key])) {
                $byType[$key] = ['label' => $label, 'count' => 0, 'total' => 0.0, 'adjusted_total' => 0.0];
            }
            $byType[$key]['count']++;
            $byType[$key]['total'] += $row['duration'];
            $byType[$key]['adjusted_total'] += $row['adjusted_duration'];
        }

        foreach ($byType as &$group) {
            $group['total'] = round($group['total'], 2);
            $group['adjusted_total'] = round($group['adjusted_total'], 2);
        }
        unset($group);

        return [
            'durasi_hrs' => $durasiHrs,
            'respontime' => $respontime,
            'subtasks' => $subtasks,
            'total_subtask' => $totalSubtask,
            'adjusted_total_subtask' => $adjustedTotal,
            'by_breakdown_type' => array_values($byType),
            'no_action' => $noAction,
            'overrun' => $overrun,
            'warnings' => $warnings,
        ];
    }

    /**
     * Date Finish paling akhir dari kumpulan subtask.
     *
     * @param  iterable $subtasks
     * @return Carbon|null
     */
    private function latestFinish(iterable $subtasks): ?Carbon
    {
        $latest = null;
        foreach ($subtasks as $st) {
            if ($st->date_finish && (!$latest || $st->date_finish->gt($latest))) {
                $latest = $st->date_finish;
            }
        }
        return $latest;
    }

    private function hoursBetween(Carbon $start, Carbon $end): float
    {
        return (float) ($start->diffInMinutes($end, false) / 60);
    }
}

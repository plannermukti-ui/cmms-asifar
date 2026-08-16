<?php

namespace Tests\Unit;

use App\Models\BreakdownType;
use App\Models\WorkOrder;
use App\Models\WoSubtask;
use App\Models\WoTask;
use App\Services\WorkOrderDurationService;
use Tests\TestCase;

class WorkOrderDurationServiceTest extends TestCase
{
    private function makeSubtask(array $attrs): WoSubtask
    {
        $st = new WoSubtask();
        $st->setRawAttributes($attrs);
        return $st;
    }

    private function makeTask(int $id, string $dateProblem, array $subtasks): WoTask
    {
        $task = new WoTask();
        $task->setRawAttributes(['id' => $id, 'problem' => 'Problem ' . $id, 'date_problem' => $dateProblem]);
        $task->setRelation('subtasks', collect($subtasks));
        return $task;
    }

    private function makeWorkOrder(?string $waktuBd, ?string $waktuRfu, array $tasks): WorkOrder
    {
        $wo = new WorkOrder();
        $wo->setRawAttributes(['id' => 1, 'waktu_bd' => $waktuBd, 'waktu_rfu' => $waktuRfu]);
        $wo->setRelation('tasks', collect($tasks));
        return $wo;
    }

    private function breakdown(string $code, string $name): BreakdownType
    {
        $bt = new BreakdownType();
        $bt->setRawAttributes(['id' => 1, 'code' => $code, 'name' => $name]);
        return $bt;
    }

    public function test_it_calculates_respon_time_from_first_task_date_problem(): void
    {
        $service = new WorkOrderDurationService();

        $wo = $this->makeWorkOrder('2026-08-10 07:00:00', '2026-08-11 07:00:00', [
            $this->makeTask(1, '2026-08-10 09:00:00', []),
        ]);

        $summary = $service->summarize($wo);

        $this->assertSame(2.0, $summary['respontime']);
        $this->assertSame(24.0, $summary['durasi_hrs']);
    }

    public function test_it_groups_subtask_durations_by_breakdown_type(): void
    {
        $service = new WorkOrderDurationService();

        $st1 = $this->makeSubtask(['id' => 1, 'action' => 'A', 'date_action' => '2026-08-10 09:00:00', 'date_finish' => '2026-08-10 12:00:00', 'duration_hours' => 3, 'breakdown_type_id' => 1]);
        $st2 = $this->makeSubtask(['id' => 2, 'action' => 'B', 'date_action' => '2026-08-10 12:00:00', 'date_finish' => '2026-08-10 15:00:00', 'duration_hours' => 3, 'breakdown_type_id' => 2]);
        $st1->setRelation('breakdownType', $this->breakdown('MEC', 'Mechanical'));
        $st2->setRelation('breakdownType', $this->breakdown('ELE', 'Electrical'));

        $wo = $this->makeWorkOrder('2026-08-10 07:00:00', '2026-08-10 20:00:00', [
            $this->makeTask(1, '2026-08-10 09:00:00', [$st1, $st2]),
        ]);

        $summary = $service->summarize($wo);

        $this->assertSame(6.0, $summary['total_subtask']);
        $this->assertCount(2, $summary['by_breakdown_type']);
        $this->assertSame(3.0, $summary['by_breakdown_type'][0]['total']);
        $this->assertSame(3.0, $summary['by_breakdown_type'][1]['total']);
    }

    public function test_it_reconciles_no_action_when_duration_exceeds_subtasks(): void
    {
        $service = new WorkOrderDurationService();

        $st1 = $this->makeSubtask(['id' => 1, 'action' => 'A', 'duration_hours' => 5, 'breakdown_type_id' => 1]);
        $st1->setRelation('breakdownType', $this->breakdown('MEC', 'Mechanical'));

        // Durasi WO = 10, Respontime = 2, Total subtask = 5 -> No Action = 3
        $wo = $this->makeWorkOrder('2026-08-10 07:00:00', '2026-08-10 17:00:00', [
            $this->makeTask(1, '2026-08-10 09:00:00', [$st1]),
        ]);

        $summary = $service->summarize($wo);

        $this->assertSame(2.0, $summary['respontime']);
        $this->assertSame(5.0, $summary['total_subtask']);
        $this->assertSame(3.0, $summary['no_action']);
        $this->assertSame(10.0, $summary['durasi_hrs']);
        $this->assertFalse($summary['overrun']);
        $this->assertSame([], $summary['warnings']);
        // Rekonsiliasi: Durasi = Respontime + Total Subtask + No Action
        $this->assertSame(
            $summary['durasi_hrs'],
            $summary['respontime'] + $summary['adjusted_total_subtask'] + $summary['no_action']
        );
    }

    public function test_it_reduces_subtask_durations_proportionally_when_duration_is_smaller(): void
    {
        $service = new WorkOrderDurationService();

        $st1 = $this->makeSubtask(['id' => 1, 'action' => 'A', 'duration_hours' => 5, 'breakdown_type_id' => 1]);
        $st2 = $this->makeSubtask(['id' => 2, 'action' => 'B', 'duration_hours' => 5, 'breakdown_type_id' => 2]);
        $st1->setRelation('breakdownType', $this->breakdown('MEC', 'Mechanical'));
        $st2->setRelation('breakdownType', $this->breakdown('ELE', 'Electrical'));

        // Durasi WO = 6, Respontime = 0, Total subtask = 10 -> scale 0.6
        $wo = $this->makeWorkOrder('2026-08-10 07:00:00', '2026-08-10 13:00:00', [
            $this->makeTask(1, '2026-08-10 07:00:00', [$st1, $st2]),
        ]);

        $summary = $service->summarize($wo);

        $this->assertTrue($summary['overrun']);
        $this->assertCount(1, $summary['warnings']);
        $this->assertSame(10.0, $summary['total_subtask']);
        $this->assertSame(6.0, $summary['adjusted_total_subtask']);
        $this->assertSame(0.0, $summary['no_action']);

        $durations = array_map(fn ($s) => $s['adjusted_duration'], $summary['subtasks']);
        $this->assertSame([3.0, 3.0], $durations);

        // Rekonsiliasi tetap seimbang
        $this->assertSame(
            $summary['durasi_hrs'],
            $summary['respontime'] + $summary['adjusted_total_subtask'] + $summary['no_action']
        );
    }

    public function test_rounded_adjusted_durations_sum_exactly_to_the_total(): void
    {
        $service = new WorkOrderDurationService();

        // Durasi WO = 1, total subtask = 3 -> scale 1/3 -> 0.33 + 0.33 + 0.33 = 0.99 (drift 0.01)
        // Baris terakhir dikoreksi menjadi 0.34 agar jumlah persis = 1.00.
        $subtasks = collect([1, 2, 3])->map(function ($id) {
            $st = $this->makeSubtask(['id' => $id, 'action' => 'A' . $id, 'duration_hours' => 1, 'breakdown_type_id' => 1]);
            $st->setRelation('breakdownType', $this->breakdown('MEC', 'Mechanical'));
            return $st;
        })->all();

        $wo = $this->makeWorkOrder('2026-08-10 07:00:00', '2026-08-10 08:00:00', [
            $this->makeTask(1, '2026-08-10 07:00:00', $subtasks),
        ]);

        $summary = $service->summarize($wo);

        $durations = array_map(fn ($s) => $s['adjusted_duration'], $summary['subtasks']);
        $this->assertSame([0.33, 0.33, 0.34], $durations);
        $this->assertSame(1.0, $summary['adjusted_total_subtask']);
        $this->assertSame(array_sum($durations), $summary['adjusted_total_subtask']);
    }

    public function test_subtask_without_finish_uses_latest_finish_from_other_subtasks(): void
    {
        $service = new WorkOrderDurationService();

        $st1 = $this->makeSubtask(['id' => 1, 'action' => 'A', 'date_action' => '2026-08-10 07:00:00', 'date_finish' => '2026-08-10 15:00:00', 'breakdown_type_id' => 1]);
        $st2 = $this->makeSubtask(['id' => 2, 'action' => 'B', 'date_action' => '2026-08-10 15:00:00', 'duration_hours' => 2, 'breakdown_type_id' => 1]);
        $st1->setRelation('breakdownType', $this->breakdown('MEC', 'Mechanical'));
        $st2->setRelation('breakdownType', $this->breakdown('MEC', 'Mechanical'));

        $wo = $this->makeWorkOrder('2026-08-10 06:00:00', '2026-08-10 20:00:00', [
            $this->makeTask(1, '2026-08-10 07:00:00', [$st1, $st2]),
        ]);

        $summary = $service->summarize($wo);

        $rows = $summary['subtasks'];
        $this->assertCount(2, $rows);

        // Subtask 1: durasi dihitung dari tanggal (15:00 - 07:00 = 8 jam)
        $this->assertSame(8.0, $rows[0]['duration']);

        // Subtask 2: tanpa Date Finish -> memakai Date Finish paling akhir (15:00)
        $this->assertSame('2026-08-10 15:00:00', $rows[1]['effective_finish']->format('Y-m-d H:i:s'));
        $this->assertSame('2026-08-10 13:00:00', $rows[1]['adjusted_date_action']->format('Y-m-d H:i:s'));
    }
}

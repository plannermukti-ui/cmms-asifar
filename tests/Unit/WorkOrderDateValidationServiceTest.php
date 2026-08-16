<?php

namespace Tests\Unit;

use App\Services\WorkOrderDateValidationService;
use PHPUnit\Framework\TestCase;

class WorkOrderDateValidationServiceTest extends TestCase
{
    public function test_it_validates_task_date_problem_against_work_order_breakdown_time(): void
    {
        $service = new WorkOrderDateValidationService();

        $errors = $service->validate([
            'waktu_bd' => '2026-08-10 08:00:00',
            'tasks' => [
                [
                    'date_problem' => '2026-08-10 07:00:00',
                    'subtasks' => [],
                ],
            ],
        ]);

        $this->assertArrayHasKey('tasks.0.date_problem', $errors);
        $this->assertSame('Date Problem tidak boleh kurang dari Waktu BD.', $errors['tasks.0.date_problem']);
    }

    public function test_it_validates_subtask_date_action_against_task_date_problem(): void
    {
        $service = new WorkOrderDateValidationService();

        $errors = $service->validate([
            'waktu_bd' => '2026-08-10 08:00:00',
            'tasks' => [
                [
                    'date_problem' => '2026-08-10 09:00:00',
                    'subtasks' => [
                        [
                            'date_action' => '2026-08-10 08:30:00',
                        ],
                    ],
                ],
            ],
        ]);

        $this->assertArrayHasKey('tasks.0.subtasks.0.date_action', $errors);
        $this->assertSame('Date Action tidak boleh kurang dari Date Problem.', $errors['tasks.0.subtasks.0.date_action']);
    }
}

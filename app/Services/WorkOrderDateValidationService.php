<?php

namespace App\Services;

class WorkOrderDateValidationService
{
    public function validate(array $data): array
    {
        $errors = [];

        $workOrderDate = null;
        if (!empty($data['waktu_bd'])) {
            $workOrderDate = \Carbon\Carbon::parse($data['waktu_bd']);
        }

        foreach ($data['tasks'] ?? [] as $taskIndex => $taskData) {
            $taskDate = null;
            if (!empty($taskData['date_problem'])) {
                $taskDate = \Carbon\Carbon::parse($taskData['date_problem']);
            }

            if ($workOrderDate && $taskDate && $taskDate->lt($workOrderDate)) {
                $errors["tasks.{$taskIndex}.date_problem"] = 'Date Problem tidak boleh kurang dari Waktu BD.';
            }

            foreach ($taskData['subtasks'] ?? [] as $subtaskIndex => $subtaskData) {
                $subtaskDate = null;
                if (!empty($subtaskData['date_action'])) {
                    $subtaskDate = \Carbon\Carbon::parse($subtaskData['date_action']);
                }

                if ($taskDate && $subtaskDate && $subtaskDate->lt($taskDate)) {
                    $errors["tasks.{$taskIndex}.subtasks.{$subtaskIndex}.date_action"] = 'Date Action tidak boleh kurang dari Date Problem.';
                }
            }
        }

        return $errors;
    }
}

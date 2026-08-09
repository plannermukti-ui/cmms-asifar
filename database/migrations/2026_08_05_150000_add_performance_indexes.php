<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add performance indexes to all major tables.
     * Uses IF NOT EXISTS pattern via Schema::hasIndex check to be idempotent.
     */
    private function addIndexSafe(string $table, string $indexName, array|string $columns): void
    {
        $cols = is_array($columns) ? implode(', ', $columns) : $columns;
        // Check if index already exists before creating
        $exists = collect(DB::select("SHOW INDEX FROM `{$table}` WHERE Key_name = ?", [$indexName]))->isNotEmpty();
        if (!$exists) {
            DB::statement("ALTER TABLE `{$table}` ADD INDEX `{$indexName}` ({$cols})");
        }
    }

    public function up(): void
    {
        // === WORK ORDERS ===
        $this->addIndexSafe('work_orders', 'idx_wo_site_status',   ['site_id', 'status_wo']);
        $this->addIndexSafe('work_orders', 'idx_wo_status_created', ['status_wo', 'created_at']);
        $this->addIndexSafe('work_orders', 'idx_wo_unit_status',   ['master_unit_id', 'status_wo']);
        $this->addIndexSafe('work_orders', 'idx_wo_created_at',    ['created_at']);
        $this->addIndexSafe('work_orders', 'idx_wo_waktu_bd',      ['waktu_bd']);

        // === HOUR METERS ===
        $this->addIndexSafe('hour_meters', 'idx_hm_site_date',  ['site_id', 'date']);
        $this->addIndexSafe('hour_meters', 'idx_hm_unit_date',  ['master_unit_id', 'date']);
        $this->addIndexSafe('hour_meters', 'idx_hm_date',       ['date']);

        // === MASTER UNITS ===
        $this->addIndexSafe('master_units', 'idx_mu_site',  ['site_id']);
        $this->addIndexSafe('master_units', 'idx_mu_model', ['unit_model_id']);

        // === TOOL TRANSACTIONS ===
        $this->addIndexSafe('tool_transactions', 'idx_tt_created_at', ['created_at']);
        $this->addIndexSafe('tool_transactions', 'idx_tt_status',     ['status']);
        $this->addIndexSafe('tool_transactions', 'idx_tt_tool',       ['tool_id']);

        // === INCIDENT REPORTS ===
        $this->addIndexSafe('incident_reports', 'idx_ir_created_at',      ['created_at']);
        $this->addIndexSafe('incident_reports', 'idx_ir_status_approval',  ['status_approval']);

        // === MESSAGES ===
        $this->addIndexSafe('messages', 'idx_msg_receiver_read', ['receiver_id', 'read_at']);
        $this->addIndexSafe('messages', 'idx_msg_sender',        ['sender_id']);

        // === USERS ===
        $this->addIndexSafe('users', 'idx_users_status', ['status']);
        $this->addIndexSafe('users', 'idx_users_site',   ['site_id']);
    }

    public function down(): void
    {
        $drops = [
            'work_orders'       => ['idx_wo_site_status', 'idx_wo_status_created', 'idx_wo_unit_status', 'idx_wo_created_at', 'idx_wo_waktu_bd'],
            'hour_meters'       => ['idx_hm_site_date', 'idx_hm_unit_date', 'idx_hm_date'],
            'master_units'      => ['idx_mu_site', 'idx_mu_model'],
            'tool_transactions' => ['idx_tt_created_at', 'idx_tt_status', 'idx_tt_tool'],
            'incident_reports'  => ['idx_ir_created_at', 'idx_ir_status_approval'],
            'messages'          => ['idx_msg_receiver_read', 'idx_msg_sender'],
            'users'             => ['idx_users_status', 'idx_users_site'],
        ];

        foreach ($drops as $table => $indexes) {
            foreach ($indexes as $index) {
                $exists = collect(DB::select("SHOW INDEX FROM `{$table}` WHERE Key_name = ?", [$index]))->isNotEmpty();
                if ($exists) {
                    DB::statement("ALTER TABLE `{$table}` DROP INDEX `{$index}`");
                }
            }
        }
    }
};

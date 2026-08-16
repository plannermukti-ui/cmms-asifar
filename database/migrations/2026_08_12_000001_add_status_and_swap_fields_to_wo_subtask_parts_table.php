<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('wo_subtask_parts', function (Blueprint $table) {
            if (!Schema::hasColumn('wo_subtask_parts', 'part_status')) {
                $table->string('part_status')->default('Replace');
            }
            if (!Schema::hasColumn('wo_subtask_parts', 'mol_pr')) {
                $table->string('mol_pr')->nullable();
            }
            if (!Schema::hasColumn('wo_subtask_parts', 'order_status')) {
                $table->string('order_status')->nullable();
            }
            if (!Schema::hasColumn('wo_subtask_parts', 'swap_type')) {
                $table->string('swap_type')->nullable();
            }
            if (!Schema::hasColumn('wo_subtask_parts', 'swap_unit_id')) {
                $table->foreignId('swap_unit_id')->nullable()->constrained('master_units')->nullOnDelete();
            }
            if (!Schema::hasColumn('wo_subtask_parts', 'swap_status')) {
                $table->string('swap_status')->nullable();
            }
            if (!Schema::hasColumn('wo_subtask_parts', 'swap_remarks')) {
                $table->text('swap_remarks')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('wo_subtask_parts', function (Blueprint $table) {
            $table->dropForeign(['swap_unit_id']);
            $table->dropColumn([
                'part_status', 'mol_pr', 'order_status', 
                'swap_type', 'swap_unit_id', 'swap_status', 'swap_remarks'
            ]);
        });
    }
};

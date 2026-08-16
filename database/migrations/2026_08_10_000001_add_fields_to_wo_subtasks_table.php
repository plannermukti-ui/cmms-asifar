<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('wo_subtasks', function (Blueprint $table) {
            if (!Schema::hasColumn('wo_subtasks', 'date_finish')) {
                $table->dateTime('date_finish')->nullable()->after('date_action');
            }
            if (!Schema::hasColumn('wo_subtasks', 'duration_hours')) {
                $table->decimal('duration_hours', 8, 2)->nullable()->after('date_finish');
            }
            if (!Schema::hasColumn('wo_subtasks', 'breakdown_type_id')) {
                $table->foreignId('breakdown_type_id')->nullable()->constrained('breakdown_types')->nullOnDelete()->after('duration_hours');
            }
        });
    }

    public function down(): void
    {
        Schema::table('wo_subtasks', function (Blueprint $table) {
            if (Schema::hasColumn('wo_subtasks', 'breakdown_type_id')) {
                $table->dropConstrainedForeignId('breakdown_type_id');
            }
            if (Schema::hasColumn('wo_subtasks', 'duration_hours')) {
                $table->dropColumn('duration_hours');
            }
            if (Schema::hasColumn('wo_subtasks', 'date_finish')) {
                $table->dropColumn('date_finish');
            }
        });
    }
};

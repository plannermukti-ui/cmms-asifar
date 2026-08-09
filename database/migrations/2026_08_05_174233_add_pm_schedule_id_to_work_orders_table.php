<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('work_orders', function (Blueprint $table) {
            $table->foreignId('pm_schedule_id')->nullable()->constrained('pm_schedules')->nullOnDelete();
        });
    }
    public function down(): void {
        Schema::table('work_orders', function (Blueprint $table) {
            $table->dropForeign(['pm_schedule_id']);
            $table->dropColumn('pm_schedule_id');
        });
    }
};

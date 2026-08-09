<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('pm_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('site_id')->nullable()->constrained('sites')->nullOnDelete();
            $table->foreignId('master_unit_id')->constrained('master_units')->cascadeOnDelete();
            $table->foreignId('pm_template_id')->constrained('pm_templates')->cascadeOnDelete();
            
            $table->decimal('last_executed_value', 10, 1)->nullable();
            $table->decimal('next_due_value', 10, 1);
            $table->enum('status_jadwal', ['Upcoming', 'Due', 'Overdue'])->default('Upcoming');
            
            $table->timestamps();
            
            // A unit should only have one active schedule for a specific template
            $table->unique(['master_unit_id', 'pm_template_id']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('pm_schedules');
    }
};

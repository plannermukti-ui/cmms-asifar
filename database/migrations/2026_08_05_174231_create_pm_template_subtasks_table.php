<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('pm_template_subtasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pm_template_task_id')->constrained('pm_template_tasks')->cascadeOnDelete();
            $table->longText('subtask_name');
            $table->integer('sequence')->default(0);
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('pm_template_subtasks');
    }
};

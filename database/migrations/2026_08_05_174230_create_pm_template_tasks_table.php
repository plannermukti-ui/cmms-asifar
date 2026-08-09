<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('pm_template_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pm_template_id')->constrained('pm_templates')->cascadeOnDelete();
            $table->longText('task_name');
            $table->integer('sequence')->default(0);
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('pm_template_tasks');
    }
};

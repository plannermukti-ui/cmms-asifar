<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('wo_subtasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wo_task_id')->constrained('wo_tasks')->cascadeOnDelete();
            $table->longText('action');
            $table->dateTime('date_action')->nullable();
            $table->dateTime('date_finish')->nullable();
            $table->decimal('duration_hours', 8, 2)->nullable();
            $table->foreignId('breakdown_type_id')->nullable()->constrained('breakdown_types')->nullOnDelete();
            $table->enum('status', ['Open','Inprogress','Completed','Cancel','Backlog'])->default('Open');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('wo_subtasks'); }
};
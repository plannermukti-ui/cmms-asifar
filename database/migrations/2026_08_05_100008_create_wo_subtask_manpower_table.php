<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('wo_subtask_manpower', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wo_subtask_id')->constrained('wo_subtasks')->cascadeOnDelete();
            $table->foreignId('mechanic_id')->constrained('mechanics')->cascadeOnDelete();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('wo_subtask_manpower'); }
};
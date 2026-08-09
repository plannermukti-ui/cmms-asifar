<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('wo_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('work_order_id')->constrained('work_orders')->cascadeOnDelete();
            $table->longText('problem');
            $table->foreignId('component_group_id')->nullable()->constrained('component_groups')->nullOnDelete();
            $table->dateTime('date_problem')->nullable();
            $table->enum('status', ['Open','Inprogress','Completed','Cancel','Backlog'])->default('Open');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('wo_tasks'); }
};
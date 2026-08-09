<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('incident_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tool_transaction_id')->constrained('tool_transactions')->cascadeOnDelete();
            $table->foreignId('mechanic_id')->constrained('mechanics')->cascadeOnDelete();
            $table->text('kronologi')->nullable();
            $table->enum('status_approval', ['Pending', 'Approved', 'Rejected'])->default('Pending');
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('incident_reports');
    }
};
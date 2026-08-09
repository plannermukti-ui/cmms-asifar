<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('stock_opnames', function (Blueprint $table) {
            $table->id();
            $table->timestamp('tanggal_audit');
            $table->enum('tipe_audit', ['ToolRoom', 'Mechanic']);
            $table->foreignId('mechanic_id')->nullable()->constrained('mechanics')->cascadeOnDelete();
            $table->foreignId('auditor_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('stock_opnames');
    }
};
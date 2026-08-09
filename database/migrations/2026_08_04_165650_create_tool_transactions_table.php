<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('tool_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tool_id')->constrained('tools')->cascadeOnDelete();
            $table->foreignId('mechanic_id')->constrained('mechanics')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete(); // Admin ToolRoom
            $table->enum('tipe_transaksi', ['Pinjam Sementara', 'Pinjam Permanen']);
            $table->timestamp('tanggal_pinjam');
            $table->integer('borrow_qty');
            $table->timestamp('tanggal_kembali')->nullable();
            $table->integer('returned_good_qty')->default(0);
            $table->integer('returned_broken_qty')->default(0);
            $table->integer('returned_lost_qty')->default(0);
            $table->enum('status', ['Borrowed', 'Returned'])->default('Borrowed');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('tool_transactions');
    }
};
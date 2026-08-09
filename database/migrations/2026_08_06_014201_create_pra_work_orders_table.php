<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pra_work_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('site_id')->nullable()->constrained('sites')->nullOnDelete();
            $table->foreignId('master_unit_id')->constrained('master_units')->cascadeOnDelete();
            $table->dateTime('waktu_bd');
            $table->decimal('hours_meter', 10, 1)->nullable();
            $table->string('lokasi_kerusakan')->nullable();
            $table->text('problem');
            $table->string('status')->default('Pending'); // Pending, Generated, Cancelled
            $table->foreignId('work_order_id')->nullable()->constrained('work_orders')->nullOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pra_work_orders');
    }
};

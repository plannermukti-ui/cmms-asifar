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
        Schema::create('hse_lotos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('work_order_id')->constrained('work_orders')->cascadeOnDelete();
            $table->foreignId('site_id')->nullable()->constrained('sites')->nullOnDelete();
            $table->string('isolation_point');
            $table->string('lock_number')->nullable();
            $table->string('tag_number')->nullable();
            $table->foreignId('applied_by')->constrained('users');
            $table->timestamp('applied_at')->nullable();
            $table->foreignId('removed_by')->nullable()->constrained('users');
            $table->timestamp('removed_at')->nullable();
            $table->string('status')->default('Active'); // Active, Removed
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hse_lotos');
    }
};

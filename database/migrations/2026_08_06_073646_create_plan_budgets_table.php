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
        Schema::create('plan_budgets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('site_id')->nullable()->constrained()->nullOnDelete();
            $table->string('period', 7); // YYYY-MM
            $table->enum('status', ['Draft', 'Approved'])->default('Draft');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('plan_budget_units', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plan_budget_id')->constrained('plan_budgets')->cascadeOnDelete();
            $table->foreignId('master_unit_id')->constrained('master_units')->cascadeOnDelete();
            $table->decimal('target_pa', 5, 2)->default(0); // percentage up to 100.00
            $table->decimal('planned_cost', 15, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plan_budget_units');
        Schema::dropIfExists('plan_budgets');
    }
};

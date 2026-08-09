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
        Schema::create('plan_budget_parts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plan_budget_unit_id')->constrained('plan_budget_units')->onDelete('cascade');
            $table->foreignId('part_id')->constrained('parts')->onDelete('restrict');
            $table->integer('qty')->default(1);
            $table->decimal('price', 15, 2)->default(0); // snapshot of part cost
            $table->decimal('total_price', 15, 2)->default(0); // qty * price
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plan_budget_parts');
    }
};

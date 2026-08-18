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
        Schema::create('part_unit_models', function (Blueprint $table) {
            $table->foreignId('part_id')->constrained()->onDelete('cascade');
            $table->foreignId('unit_model_id')->constrained()->onDelete('cascade');
            $table->primary(['part_id', 'unit_model_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('part_unit_models');
    }
};

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
        Schema::create('production_fleets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('production_id');
            $table->unsignedBigInteger('digger_id'); // MasterUnit
            $table->string('material_type'); // OB, Coal, Top Soil, Mud, dll
            $table->decimal('distance', 8, 2)->nullable(); // Distance in KM or meters
            $table->timestamps();

            $table->foreign('production_id')->references('id')->on('productions')->onDelete('cascade');
            $table->foreign('digger_id')->references('id')->on('master_units')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('production_fleets');
    }
};

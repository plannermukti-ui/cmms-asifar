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
        Schema::create('production_haulers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('production_fleet_id');
            $table->unsignedBigInteger('hauler_id');
            $table->decimal('payload', 8, 2)->default(0); // Capacity in Ton/BCM
            $table->json('hourly_ritasi')->nullable(); // Store {"07:00": 4, "08:00": 3}
            $table->integer('total_ritasi')->default(0); 
            $table->timestamps();

            $table->foreign('production_fleet_id')->references('id')->on('production_fleets')->onDelete('cascade');
            $table->foreign('hauler_id')->references('id')->on('master_units')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('production_haulers');
    }
};

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
        Schema::create('production_delays', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('production_id');
            $table->unsignedBigInteger('production_fleet_id')->nullable();
            $table->time('start_time');
            $table->time('end_time');
            $table->string('delay_code'); // Rain, Slippery, Breakdown, No Operator, dll
            $table->text('remarks')->nullable();
            $table->timestamps();

            $table->foreign('production_id')->references('id')->on('productions')->onDelete('cascade');
            $table->foreign('production_fleet_id')->references('id')->on('production_fleets')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('production_delays');
    }
};

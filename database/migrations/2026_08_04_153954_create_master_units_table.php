<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('master_units', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_unit', 20)->unique();
            $table->foreignId('unit_type_id')->constrained('unit_types');
            $table->foreignId('unit_model_id')->nullable()->constrained('unit_models');
            $table->string('sn_chassis', 50)->nullable();
            $table->string('engine_model', 100)->nullable();
            $table->string('sn_engine', 50)->nullable();
            $table->string('engine_make', 50)->nullable();
            $table->string('capacity', 50)->nullable();
            $table->string('no_polisi', 30)->nullable();
            $table->string('attachments', 100)->nullable();
            $table->string('hp', 20)->nullable();
            $table->string('kw', 20)->nullable();
            $table->string('perakitan', 20)->nullable();
            $table->string('date_receive', 20)->nullable();
            $table->string('dari', 50)->nullable();
            $table->string('location', 50)->nullable();
            $table->string('remarks', 50)->nullable();
            $table->boolean('service')->default(0);
            $table->boolean('active')->default(1);
            $table->string('site', 10);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('master_units');
    }
};

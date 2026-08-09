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
        Schema::create('far_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('far_id')->constrained('fars')->onDelete('cascade');
            $table->string('component')->nullable();
            $table->text('observation')->nullable();
            $table->string('photo_path')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('far_attachments');
    }
};

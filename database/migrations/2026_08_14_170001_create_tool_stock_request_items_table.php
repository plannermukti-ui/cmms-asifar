<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tool_stock_request_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tool_stock_request_id')->constrained('tool_stock_requests')->cascadeOnDelete();
            $table->foreignId('tool_id')->constrained('tools')->cascadeOnDelete();
            $table->enum('location_type', ['ToolRoom', 'Mechanic']);
            $table->foreignId('mechanic_id')->nullable()->constrained('mechanics')->cascadeOnDelete();
            $table->integer('quantity');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tool_stock_request_items');
    }
};

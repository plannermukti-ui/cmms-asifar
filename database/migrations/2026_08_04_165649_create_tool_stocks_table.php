<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('tool_stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tool_id')->constrained('tools')->cascadeOnDelete();
            $table->enum('location_type', ['ToolRoom', 'Mechanic']);
            $table->foreignId('mechanic_id')->nullable()->constrained('mechanics')->cascadeOnDelete();
            $table->integer('quantity')->default(0);
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('tool_stocks');
    }
};
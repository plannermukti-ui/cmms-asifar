<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('pm_templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('unit_model_id')->constrained('unit_models')->cascadeOnDelete();
            $table->string('name');
            $table->enum('interval_type', ['hour_meter', 'kilometer', 'days'])->default('hour_meter');
            $table->integer('interval_value');
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('pm_templates');
    }
};

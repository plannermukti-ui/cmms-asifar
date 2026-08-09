<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('wo_categories', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('level'); // 1-5
            $table->string('name');
            $table->timestamps();
            $table->unique(['level', 'name']);
        });
    }
    public function down(): void { Schema::dropIfExists('wo_categories'); }
};
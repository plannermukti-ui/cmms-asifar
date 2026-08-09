<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('parts', function (Blueprint $table) {
            $table->id();
            $table->string('part_number')->unique();
            $table->string('part_description');
            $table->string('satuan')->nullable();
            $table->decimal('cost', 15, 2)->default(0);
            $table->string('kategori_1')->nullable();
            $table->string('kategori_2')->nullable();
            $table->string('kategori_3')->nullable();
            $table->string('kategori_4')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('parts'); }
};
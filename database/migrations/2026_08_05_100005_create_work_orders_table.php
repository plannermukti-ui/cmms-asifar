<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('work_orders', function (Blueprint $table) {
            $table->id();
            $table->string('no_wo')->unique();
            $table->enum('status_wo', ['Open','Inprogress','Completed','Cancel','Backlog'])->default('Open');
            $table->enum('tipe_wo', ['BD','Plan'])->default('BD');
            $table->enum('downtime_code', ['Schedule','Unschedule','Accident'])->default('Unschedule');

            // Unit identity
            $table->foreignId('master_unit_id')->constrained('master_units')->cascadeOnDelete();
            $table->decimal('hours_meter', 10, 1)->nullable();

            // Waktu
            $table->dateTime('waktu_bd')->nullable();
            $table->dateTime('waktu_rfu')->nullable();

            // Klasifikasi
            $table->foreignId('breakdown_type_id')->nullable()->constrained('breakdown_types')->nullOnDelete();
            $table->foreignId('component_group_id')->nullable()->constrained('component_groups')->nullOnDelete();
            $table->unsignedBigInteger('wo_category_1_id')->nullable();
            $table->unsignedBigInteger('wo_category_2_id')->nullable();
            $table->unsignedBigInteger('wo_category_3_id')->nullable();
            $table->unsignedBigInteger('wo_category_4_id')->nullable();
            $table->unsignedBigInteger('wo_category_5_id')->nullable();

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->foreign('wo_category_1_id')->references('id')->on('wo_categories')->nullOnDelete();
            $table->foreign('wo_category_2_id')->references('id')->on('wo_categories')->nullOnDelete();
            $table->foreign('wo_category_3_id')->references('id')->on('wo_categories')->nullOnDelete();
            $table->foreign('wo_category_4_id')->references('id')->on('wo_categories')->nullOnDelete();
            $table->foreign('wo_category_5_id')->references('id')->on('wo_categories')->nullOnDelete();
        });
    }
    public function down(): void { Schema::dropIfExists('work_orders'); }
};
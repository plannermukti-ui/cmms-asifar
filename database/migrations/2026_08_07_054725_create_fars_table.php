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
        Schema::create('fars', function (Blueprint $table) {
            $table->id();
            $table->string('no_far')->unique();
            $table->foreignId('site_id')->constrained('sites')->onDelete('cascade');
            $table->foreignId('work_order_id')->nullable()->constrained('work_orders')->onDelete('set null');
            $table->foreignId('master_unit_id')->nullable()->constrained('master_units')->onDelete('set null');
            $table->foreignId('reported_by')->nullable()->constrained('users')->onDelete('set null');
            $table->date('date_reported')->nullable();
            $table->date('date_of_failure')->nullable();
            $table->integer('smu_at_failure')->nullable();
            
            $table->string('component_part_no')->nullable();
            $table->string('component_description')->nullable();
            $table->string('part_no_causing_failure')->nullable();

            $table->date('last_comp_date')->nullable();
            $table->integer('last_comp_smu')->nullable();
            $table->integer('hours_of_component')->nullable();

            $table->date('last_oil_date_taken')->nullable();
            $table->date('last_oil_date_sent')->nullable();
            $table->date('last_oil_date_received')->nullable();
            $table->string('last_oil_eval')->nullable();

            $table->text('failure_outline')->nullable();
            $table->text('background')->nullable();
            $table->text('failure_analysis')->nullable();
            $table->text('conclusion')->nullable();

            $table->enum('status', ['Draft', 'Submitted', 'Approved'])->default('Draft');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fars');
    }
};

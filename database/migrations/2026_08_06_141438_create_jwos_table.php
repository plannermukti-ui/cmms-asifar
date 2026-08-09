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
        Schema::create('jwos', function (Blueprint $table) {
            $table->id();
            $table->string('no_jwo')->unique();
            $table->foreignId('vendor_id')->constrained('vendors')->restrictOnDelete();
            $table->foreignId('work_order_id')->nullable()->constrained('work_orders')->nullOnDelete();
            $table->foreignId('unit_id')->nullable()->constrained('master_units')->nullOnDelete();
            $table->foreignId('component_group_id')->nullable()->constrained('component_groups')->nullOnDelete();
            $table->foreignId('part_id')->nullable()->constrained('parts')->nullOnDelete();
            $table->text('problem_description');
            $table->text('request_action')->nullable();
            $table->string('status')->default('Progress Site'); // Progress Site, Sent, Progress Vendor, Completed, Cancelled
            $table->date('date_sent')->nullable();
            $table->date('date_expected')->nullable();
            $table->date('date_returned')->nullable();
            $table->decimal('cost', 15, 2)->nullable();
            $table->text('remarks')->nullable();
            $table->foreignId('site_id')->nullable()->constrained('sites')->nullOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jwos');
    }
};

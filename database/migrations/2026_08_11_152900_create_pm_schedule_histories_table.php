<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pm_schedule_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pm_schedule_id')->constrained()->cascadeOnDelete();
            $table->date('executed_at');
            $table->string('work_order_no')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pm_schedule_histories');
    }
};

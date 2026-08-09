<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('approval_matrices')) {
            Schema::create('approval_matrices', function (Blueprint $table) {
                $table->id();
                $table->string('module_name');
                $table->unsignedInteger('sequence');
                $table->unsignedBigInteger('role_id');
                $table->string('description')->nullable();
                $table->timestamps();
                $table->softDeletes();
                $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
                $table->index(['module_name', 'sequence']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('approval_matrices');
    }
};

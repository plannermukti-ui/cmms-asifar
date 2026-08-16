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
        Schema::table('work_orders', function (Blueprint $table) {
            $table->dropForeign(['breakdown_type_id']);
            $table->dropForeign(['component_group_id']);
            $table->dropColumn(['breakdown_type_id', 'component_group_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('work_orders', function (Blueprint $table) {
            $table->foreignId('breakdown_type_id')->nullable()->constrained('breakdown_types')->nullOnDelete();
            $table->foreignId('component_group_id')->nullable()->constrained('component_groups')->nullOnDelete();
        });
    }
};

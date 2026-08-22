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
        Schema::table('hse_lotos', function (Blueprint $table) {
            $table->foreignId('applied_mechanic_id')->nullable()->after('tag_number')->constrained('mechanics')->nullOnDelete();
            $table->foreignId('removed_mechanic_id')->nullable()->after('removed_by')->constrained('mechanics')->nullOnDelete();
            $table->unsignedBigInteger('applied_by')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hse_lotos', function (Blueprint $table) {
            $table->dropForeign(['applied_mechanic_id']);
            $table->dropColumn('applied_mechanic_id');
            $table->dropForeign(['removed_mechanic_id']);
            $table->dropColumn('removed_mechanic_id');
        });
    }
};

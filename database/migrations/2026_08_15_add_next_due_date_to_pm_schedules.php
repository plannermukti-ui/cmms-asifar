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
        Schema::table('pm_schedules', function (Blueprint $table) {
            if (!Schema::hasColumn('pm_schedules', 'next_due_date')) {
                $table->date('next_due_date')->nullable()->after('next_due_value');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pm_schedules', function (Blueprint $table) {
            if (Schema::hasColumn('pm_schedules', 'next_due_date')) {
                $table->dropColumn('next_due_date');
            }
        });
    }
};

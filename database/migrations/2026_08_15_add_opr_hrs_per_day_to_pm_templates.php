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
        Schema::table('pm_templates', function (Blueprint $table) {
            if (!Schema::hasColumn('pm_templates', 'opr_hrs_per_day')) {
                $table->decimal('opr_hrs_per_day', 10, 1)->default(20)->after('interval_value');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pm_templates', function (Blueprint $table) {
            if (Schema::hasColumn('pm_templates', 'opr_hrs_per_day')) {
                $table->dropColumn('opr_hrs_per_day');
            }
        });
    }
};

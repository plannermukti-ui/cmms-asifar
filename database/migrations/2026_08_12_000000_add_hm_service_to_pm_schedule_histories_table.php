<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('pm_schedule_histories', function (Blueprint $table) {
            if (!Schema::hasColumn('pm_schedule_histories', 'hm_service')) {
                $table->decimal('hm_service', 10, 1)->nullable()->after('pm_schedule_id');
            }
        });
    }

    public function down()
    {
        Schema::table('pm_schedule_histories', function (Blueprint $table) {
            $table->dropColumn('hm_service');
        });
    }
};

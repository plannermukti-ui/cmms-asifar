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
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('site_id')->nullable()->constrained('sites')->nullOnDelete();
        });

        Schema::table('master_units', function (Blueprint $table) {
            $table->foreignId('site_id')->nullable()->constrained('sites')->nullOnDelete();
            // Drop old string 'site' later if needed, but for now we keep it or rename it.
            // Let's drop the old string 'site' column because it will conflict with relations.
            // Actually, we must drop it safely or rename it.
            // Since there's existing data, we should migrate the string 'site' into 'sites' table first.
            // We'll do that in a seeder/command. For now, rename the old one to 'legacy_site'.
        });

        Schema::table('parts', function (Blueprint $table) {
            $table->foreignId('site_id')->nullable()->constrained('sites')->nullOnDelete();
        });

        Schema::table('tools', function (Blueprint $table) {
            $table->foreignId('site_id')->nullable()->constrained('sites')->nullOnDelete();
        });

        Schema::table('mechanics', function (Blueprint $table) {
            $table->foreignId('site_id')->nullable()->constrained('sites')->nullOnDelete();
        });

        Schema::table('work_orders', function (Blueprint $table) {
            $table->foreignId('site_id')->nullable()->constrained('sites')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['site_id']);
            $table->dropColumn('site_id');
        });
        Schema::table('master_units', function (Blueprint $table) {
            $table->dropForeign(['site_id']);
            $table->dropColumn('site_id');
        });
        Schema::table('parts', function (Blueprint $table) {
            $table->dropForeign(['site_id']);
            $table->dropColumn('site_id');
        });
        Schema::table('tools', function (Blueprint $table) {
            $table->dropForeign(['site_id']);
            $table->dropColumn('site_id');
        });
        Schema::table('mechanics', function (Blueprint $table) {
            $table->dropForeign(['site_id']);
            $table->dropColumn('site_id');
        });
        Schema::table('work_orders', function (Blueprint $table) {
            $table->dropForeign(['site_id']);
            $table->dropColumn('site_id');
        });
    }
};

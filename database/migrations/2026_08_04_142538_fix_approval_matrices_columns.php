<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('approval_matrices', function (Blueprint $table) {
            // Rename kolom lama ke nama baru sesuai model
            if (Schema::hasColumn('approval_matrices', 'modul')) {
                $table->renameColumn('modul', 'module_name');
            }
            if (Schema::hasColumn('approval_matrices', 'urutan')) {
                $table->renameColumn('urutan', 'sequence');
            }
            // Tambah kolom description jika belum ada
            if (!Schema::hasColumn('approval_matrices', 'description')) {
                $table->string('description')->nullable()->after('role_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('approval_matrices', function (Blueprint $table) {
            if (Schema::hasColumn('approval_matrices', 'module_name')) {
                $table->renameColumn('module_name', 'modul');
            }
            if (Schema::hasColumn('approval_matrices', 'sequence')) {
                $table->renameColumn('sequence', 'urutan');
            }
        });
    }
};

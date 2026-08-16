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
        Schema::table('stock_opnames', function (Blueprint $table) {
            $table->string('status')->default('Approved')->after('mechanic_id');
            $table->string('signed_document')->nullable()->after('status');
            $table->foreignId('approver_id')->nullable()->constrained('users')->nullOnDelete()->after('signed_document');
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete()->after('approver_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stock_opnames', function (Blueprint $table) {
            $table->dropForeign(['approver_id']);
            $table->dropForeign(['approved_by']);
            $table->dropColumn(['status', 'signed_document', 'approver_id', 'approved_by']);
        });
    }
};

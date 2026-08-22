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
        Schema::table('fuel_receivings', function (Blueprint $table) {
            $table->foreignId('intended_approver_id')->nullable()->after('received_by')->constrained('users')->nullOnDelete();
        });

        Schema::table('fuel_transfers', function (Blueprint $table) {
            $table->string('transfer_method')->default('Direct Pump')->after('destination_storage_id'); // 'Direct Pump', 'Via Fuel Truck'
            $table->foreignId('fuel_truck_id')->nullable()->after('transfer_method')->constrained('fuel_trucks')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fuel_receivings', function (Blueprint $table) {
            $table->dropForeign(['intended_approver_id']);
            $table->dropColumn('intended_approver_id');
        });

        Schema::table('fuel_transfers', function (Blueprint $table) {
            $table->dropForeign(['fuel_truck_id']);
            $table->dropColumn(['transfer_method', 'fuel_truck_id']);
        });
    }
};

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
        // 1. Tangki Timbun / SPBU Station di Site
        Schema::create('fuel_storages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('site_id')->nullable()->constrained('sites')->nullOnDelete();
            $table->string('code')->unique(); // ST-01, FS-PIT1
            $table->string('name'); // Main Storage Tank 50KL, Fuel Station Pit Barat
            $table->string('type')->default('Main Storage'); // Main Storage, Fuel Station, Tangki Duduk / Temporary
            $table->decimal('capacity', 12, 2)->default(0); // Kapasitas maks (Liter)
            $table->decimal('current_stock', 12, 2)->default(0); // Stok aktual (Liter)
            $table->decimal('min_stock_alert', 12, 2)->default(5000); // Batas minimal alert
            $table->decimal('current_totalizer', 14, 2)->default(0); // Totalizer pompa storage
            $table->string('location')->nullable();
            $table->boolean('is_active')->default(true);
            $table->text('remarks')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // 2. Truk Tangki Pengantar dari Vendor / Supplier
        Schema::create('fuel_supplier_trucks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')->constrained('vendors')->cascadeOnDelete();
            $table->foreignId('site_id')->nullable()->constrained('sites')->nullOnDelete();
            $table->string('truck_plat_nomor'); // No Polisi Truk Supplier
            $table->string('transportir_name')->nullable(); // Nama PT Transportir
            $table->string('driver_name'); // Nama Supir
            $table->string('driver_phone')->nullable();
            $table->decimal('compartment_capacity', 12, 2)->default(0); // Kapasitas tangki truk (Liter)
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 3. Unit Mobile Fuel Dispenser (Fuel Truck) yang terhubung ke Master Unit
        Schema::create('fuel_trucks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('master_unit_id')->constrained('master_units')->cascadeOnDelete();
            $table->foreignId('site_id')->nullable()->constrained('sites')->nullOnDelete();
            $table->decimal('capacity', 12, 2)->default(0); // Kapasitas tangki Fuel Truck (Liter)
            $table->decimal('current_stock', 12, 2)->default(0); // Stok saat ini di Fuel Truck
            $table->decimal('initial_totalizer', 14, 2)->default(0); // Totalizer flowmeter awal
            $table->decimal('current_totalizer', 14, 2)->default(0); // Totalizer flowmeter terkini
            $table->string('flowmeter_serial_number')->nullable(); // No Seri Flowmeter
            $table->string('dispenser_brand')->nullable(); // Merk Flowmeter / Pompa
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 4. Penerimaan BBM dari Vendor (Inbound & Sonding)
        Schema::create('fuel_receivings', function (Blueprint $table) {
            $table->id();
            $table->string('receiving_number')->unique(); // FR-202608-0001
            $table->foreignId('site_id')->nullable()->constrained('sites')->nullOnDelete();
            $table->foreignId('fuel_storage_id')->constrained('fuel_storages')->cascadeOnDelete();
            $table->foreignId('vendor_id')->constrained('vendors')->cascadeOnDelete();
            $table->foreignId('fuel_supplier_truck_id')->nullable()->constrained('fuel_supplier_trucks')->nullOnDelete();
            $table->string('delivery_order_number'); // No DO / Surat Jalan Supplier
            $table->string('po_number')->nullable();
            $table->dateTime('date_receive');
            $table->string('truck_plat_nomor')->nullable();
            $table->string('driver_name')->nullable();
            
            // Parameter Sonding
            $table->decimal('sonding_awal_cm', 8, 2)->nullable();
            $table->decimal('sonding_akhir_cm', 8, 2)->nullable();
            $table->decimal('density', 6, 4)->nullable(); // Misal: 0.8450
            $table->decimal('temperature', 5, 2)->nullable(); // Celcius
            
            // Volume
            $table->decimal('do_volume_liters', 12, 2)->default(0); // Volume DO
            $table->decimal('received_volume_liters', 12, 2)->default(0); // Volume aktual diterima
            $table->decimal('losses_volume_liters', 12, 2)->default(0); // Variance / Susut
            
            // Totalizer Pompa Penerima
            $table->decimal('totalizer_before', 14, 2)->nullable();
            $table->decimal('totalizer_after', 14, 2)->nullable();
            
            $table->string('document_scan')->nullable(); // File upload DO / BAP
            $table->text('notes')->nullable();
            $table->string('status')->default('Submitted'); // Submitted, Approved, Rejected
            $table->foreignId('received_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('approved_at')->nullable();
            $table->text('rejected_reason')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // 5. Mutasi Antar Storage / Station
        Schema::create('fuel_transfers', function (Blueprint $table) {
            $table->id();
            $table->string('transfer_number')->unique(); // TR-202608-0001
            $table->foreignId('site_id')->nullable()->constrained('sites')->nullOnDelete();
            $table->foreignId('source_storage_id')->constrained('fuel_storages')->cascadeOnDelete();
            $table->foreignId('destination_storage_id')->constrained('fuel_storages')->cascadeOnDelete();
            $table->dateTime('transfer_date');
            $table->decimal('volume_liters', 12, 2);
            $table->decimal('source_totalizer_before', 14, 2)->nullable();
            $table->decimal('source_totalizer_after', 14, 2)->nullable();
            $table->decimal('dest_totalizer_before', 14, 2)->nullable();
            $table->decimal('dest_totalizer_after', 14, 2)->nullable();
            $table->string('operator_name')->nullable();
            $table->text('notes')->nullable();
            $table->string('status')->default('Completed');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        // 6. Pengisian BBM dari Storage ke Mobile Fuel Truck
        Schema::create('fuel_truck_fillings', function (Blueprint $table) {
            $table->id();
            $table->string('refill_number')->unique(); // FTF-202608-0001
            $table->foreignId('site_id')->nullable()->constrained('sites')->nullOnDelete();
            $table->foreignId('fuel_storage_id')->constrained('fuel_storages')->cascadeOnDelete();
            $table->foreignId('fuel_truck_id')->constrained('fuel_trucks')->cascadeOnDelete();
            $table->dateTime('fill_date');
            $table->string('shift')->default('Shift 1'); // Shift 1 (Siang), Shift 2 (Malam)
            $table->decimal('storage_totalizer_before', 14, 2)->nullable();
            $table->decimal('storage_totalizer_after', 14, 2)->nullable();
            $table->decimal('volume_liters', 12, 2);
            $table->decimal('truck_stock_before', 12, 2)->default(0);
            $table->decimal('truck_stock_after', 12, 2)->default(0);
            $table->string('driver_fuel_truck')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        // 7. Sesi / Batch Distribusi Fuel Truck per Shift (Totalizer Awal & Akhir Shift)
        Schema::create('fuel_distribution_shifts', function (Blueprint $table) {
            $table->id();
            $table->string('shift_doc_number')->unique(); // FDS-202608-0001
            $table->foreignId('site_id')->nullable()->constrained('sites')->nullOnDelete();
            $table->foreignId('fuel_truck_id')->constrained('fuel_trucks')->cascadeOnDelete();
            $table->date('date');
            $table->string('shift')->default('Shift 1'); // Shift 1, Shift 2
            $table->string('fuelman_name'); // Nama Petugas Fuelman / Supir FT
            $table->decimal('totalizer_start', 14, 2); // Totalizer Awal Shift
            $table->decimal('totalizer_end', 14, 2)->nullable(); // Totalizer Akhir Shift
            $table->decimal('total_liters_flowmeter', 12, 2)->default(0); // Delta Totalizer
            $table->decimal('total_liters_distributed', 12, 2)->default(0); // Sum dari unit-unit
            $table->decimal('variance_liters', 12, 2)->default(0); // Selisih Flowmeter vs Total Rincian
            $table->string('status')->default('Open'); // Open, Closed
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('closed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('closed_at')->nullable();
            $table->timestamps();
        });

        // 8. Rincian Distribusi Fuel ke Setiap Unit Operasional
        Schema::create('fuel_distributions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fuel_distribution_shift_id')->constrained('fuel_distribution_shifts')->cascadeOnDelete();
            $table->foreignId('fuel_truck_id')->constrained('fuel_trucks')->cascadeOnDelete();
            $table->foreignId('master_unit_id')->constrained('master_units')->cascadeOnDelete();
            $table->foreignId('site_id')->nullable()->constrained('sites')->nullOnDelete();
            $table->dateTime('dispense_time'); // Waktu pengisian
            $table->decimal('meter_reading', 10, 2)->nullable(); // Nilai HM atau KM gabungan
            $table->string('meter_type')->default('HM'); // HM atau KM
            $table->string('unit_operator_name')->nullable(); // Operator Unit Penerima
            $table->decimal('volume_liters', 12, 2); // Total Liter diisikan
            $table->string('location')->nullable(); // Pit, Disposal, Workshop, Front
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        // 9. Berita Acara Pergantian / Kerusakan / Kalibrasi Flowmeter
        Schema::create('fuel_flowmeter_adjustments', function (Blueprint $table) {
            $table->id();
            $table->string('adjustment_number')->unique(); // BAF-202608-0001
            $table->foreignId('site_id')->nullable()->constrained('sites')->nullOnDelete();
            $table->string('device_type'); // 'fuel_storage' atau 'fuel_truck'
            $table->unsignedBigInteger('device_id'); // ID Storage atau ID Fuel Truck
            $table->string('incident_type'); // Replacement, Damage / Breakdown, Recalibration / Adjustment
            $table->date('incident_date');
            
            $table->string('old_flowmeter_serial')->nullable();
            $table->decimal('old_totalizer_final', 14, 2)->default(0);
            $table->string('new_flowmeter_serial')->nullable();
            $table->decimal('new_totalizer_initial', 14, 2)->default(0);
            
            $table->text('reason'); // Alasan penggantian/kerusakan
            $table->string('document_scan')->nullable(); // Upload dokumen fisik
            $table->string('signed_by_manager_name')->nullable(); // Nama Manager Site yang menandatangani
            $table->foreignId('manager_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('signed_at')->nullable();
            $table->string('status')->default('Approved'); // Draft, Approved
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        // 10. Buku Kas / Kartu Stok BBM (Audit Trail Mutasi)
        Schema::create('fuel_stock_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('site_id')->nullable()->constrained('sites')->nullOnDelete();
            $table->string('reference_type'); // 'fuel_storage' atau 'fuel_truck'
            $table->unsignedBigInteger('reference_id'); // ID Storage / Truck
            $table->string('transaction_type'); // Receiving, Transfer In, Transfer Out, Truck Refill In, Truck Refill Out, Unit Dispensing, Adjustment
            $table->string('transaction_number')->nullable();
            $table->dateTime('date_time');
            $table->decimal('qty_in', 12, 2)->default(0);
            $table->decimal('qty_out', 12, 2)->default(0);
            $table->decimal('balance_after', 12, 2)->default(0);
            $table->decimal('totalizer_record', 14, 2)->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fuel_stock_logs');
        Schema::dropIfExists('fuel_flowmeter_adjustments');
        Schema::dropIfExists('fuel_distributions');
        Schema::dropIfExists('fuel_distribution_shifts');
        Schema::dropIfExists('fuel_truck_fillings');
        Schema::dropIfExists('fuel_transfers');
        Schema::dropIfExists('fuel_receivings');
        Schema::dropIfExists('fuel_trucks');
        Schema::dropIfExists('fuel_supplier_trucks');
        Schema::dropIfExists('fuel_storages');
    }
};

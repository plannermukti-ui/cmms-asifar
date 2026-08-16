<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // 1. Create part_categories table
        Schema::create('part_categories', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // 'kategori_1', 'kategori_2', etc.
            $table->string('name');
            $table->timestamps();
        });

        // 2. Add expenditure_type and foreign keys to parts
        Schema::table('parts', function (Blueprint $table) {
            $table->string('expenditure_type', 50)->nullable()->after('cost');
            $table->unsignedBigInteger('kategori_1_id')->nullable()->after('kategori_4');
            $table->unsignedBigInteger('kategori_2_id')->nullable()->after('kategori_1_id');
            $table->unsignedBigInteger('kategori_3_id')->nullable()->after('kategori_2_id');
            $table->unsignedBigInteger('kategori_4_id')->nullable()->after('kategori_3_id');
        });

        // 3. Migrate existing data
        $parts = DB::table('parts')->get();
        foreach ($parts as $part) {
            $updates = [];
            for ($i = 1; $i <= 4; $i++) {
                $oldField = "kategori_{$i}";
                $oldValue = $part->$oldField;
                if (!empty($oldValue)) {
                    // find or create category
                    $cat = DB::table('part_categories')
                        ->where('type', $oldField)
                        ->where('name', $oldValue)
                        ->first();
                    
                    if (!$cat) {
                        $catId = DB::table('part_categories')->insertGetId([
                            'type' => $oldField,
                            'name' => $oldValue,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    } else {
                        $catId = $cat->id;
                    }
                    $updates["kategori_{$i}_id"] = $catId;
                }
            }
            if (!empty($updates)) {
                DB::table('parts')->where('id', $part->id)->update($updates);
            }
        }

        // 4. Drop old columns
        Schema::table('parts', function (Blueprint $table) {
            $table->dropColumn(['kategori_1', 'kategori_2', 'kategori_3', 'kategori_4']);
        });
    }

    public function down()
    {
        Schema::table('parts', function (Blueprint $table) {
            $table->string('kategori_1')->nullable();
            $table->string('kategori_2')->nullable();
            $table->string('kategori_3')->nullable();
            $table->string('kategori_4')->nullable();
        });

        $parts = DB::table('parts')->get();
        foreach ($parts as $part) {
            $updates = [];
            for ($i = 1; $i <= 4; $i++) {
                $idField = "kategori_{$i}_id";
                if ($part->$idField) {
                    $cat = DB::table('part_categories')->where('id', $part->$idField)->first();
                    if ($cat) {
                        $updates["kategori_{$i}"] = $cat->name;
                    }
                }
            }
            if (!empty($updates)) {
                DB::table('parts')->where('id', $part->id)->update($updates);
            }
        }

        Schema::table('parts', function (Blueprint $table) {
            $table->dropColumn(['expenditure_type', 'kategori_1_id', 'kategori_2_id', 'kategori_3_id', 'kategori_4_id']);
        });

        Schema::dropIfExists('part_categories');
    }
};

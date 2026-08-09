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
        Schema::create('document_signatures', function (Blueprint $table) {
            $table->id();
            $table->morphs('document'); // Adds document_type and document_id
            $table->string('sign_type'); // e.g., 'dikerjakan', 'diperiksa', 'ditinjau', 'disetujui'
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('role_name')->nullable(); // The role they signed as
            $table->timestamps();

            // Prevent duplicate signatures for the same type on the same document
            $table->unique(['document_type', 'document_id', 'sign_type'], 'doc_sign_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_signatures');
    }
};

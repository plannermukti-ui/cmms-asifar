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
        Schema::create('meetings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('site_id')->nullable()->constrained('sites')->nullOnDelete();
            $table->string('meeting_number')->unique();
            $table->string('title');
            $table->string('meeting_type')->default('Daily Standup'); // Daily Standup, Weekly Review, Monthly Review, Safety Talk, Ad-hoc
            $table->date('meeting_date');
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->string('location')->nullable();
            $table->string('leader_name')->nullable();
            $table->string('notetaker_name')->nullable();
            $table->text('attendees')->nullable(); // List or JSON of participants
            $table->text('agenda')->nullable();
            $table->text('general_notes')->nullable();
            $table->string('status')->default('Published'); // Draft, Published, Closed
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('meeting_action_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('meeting_id')->constrained('meetings')->cascadeOnDelete();
            $table->foreignId('parent_action_item_id')->nullable()->constrained('meeting_action_items')->nullOnDelete(); // for rollovers
            $table->integer('item_number')->default(1);
            $table->string('issue');
            $table->text('discussion')->nullable(); // decisions or action plan
            $table->string('category')->default('General'); // Breakdown & WO, Sparepart & Logistic, Manpower, HSE & Safety, Operations & Plant, Budget & Admin, General
            $table->foreignId('pic_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('pic_name')->nullable();
            $table->string('priority')->default('Medium'); // Low, Medium, High, Critical
            $table->date('due_date')->nullable();
            $table->unsignedTinyInteger('progress_percent')->default(0); // 0 - 100
            $table->string('status')->default('Open'); // Open, In Progress, Waiting Part, Completed, Cancelled
            $table->text('latest_update')->nullable();
            $table->dateTime('completed_at')->nullable();
            $table->string('link_type')->nullable(); // WorkOrder, MasterUnit, Part, FAR
            $table->unsignedBigInteger('link_id')->nullable();
            $table->timestamps();
        });

        Schema::create('meeting_action_item_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('action_item_id')->constrained('meeting_action_items')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->unsignedTinyInteger('progress_percent')->default(0);
            $table->string('status')->default('Open');
            $table->text('note');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meeting_action_item_logs');
        Schema::dropIfExists('meeting_action_items');
        Schema::dropIfExists('meetings');
    }
};

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
        Schema::create('study_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('activity_type', ['flashcard_review', 'exercise', 'shadowing', 'content_view']);
            $table->string('activityable_type');
            $table->unsignedBigInteger('activityable_id');
            $table->integer('duration_seconds');
            $table->integer('xp_earned');
            $table->timestamps();
            
            $table->index(['activityable_type', 'activityable_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('study_activities');
    }
};

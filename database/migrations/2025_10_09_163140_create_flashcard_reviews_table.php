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
        Schema::create('flashcard_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('flashcard_id')->constrained()->onDelete('cascade');
            $table->tinyInteger('rating'); // 1-4: Again, Hard, Good, Easy
            $table->integer('duration_seconds');
            $table->integer('previous_interval');
            $table->integer('new_interval');
            $table->timestamp('reviewed_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flashcard_reviews');
    }
};

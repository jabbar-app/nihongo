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
        Schema::create('phrase_review_queues', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('phrase_id')->constrained()->onDelete('cascade');
            $table->foreignId('drill_id')->constrained()->onDelete('cascade');
            $table->integer('incorrect_count')->default(1);
            $table->timestamp('last_incorrect_at');
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
            
            $table->unique(['user_id', 'phrase_id', 'drill_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('phrase_review_queues');
    }
};

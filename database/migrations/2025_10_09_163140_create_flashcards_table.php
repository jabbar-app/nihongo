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
        Schema::create('flashcards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('phrase_id')->nullable()->constrained()->onDelete('set null');
            $table->string('front'); // Japanese
            $table->string('back'); // English
            $table->string('romaji');
            $table->decimal('ease_factor', 3, 2)->default(2.5);
            $table->integer('interval')->default(0); // days
            $table->integer('repetitions')->default(0);
            $table->timestamp('next_review_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flashcards');
    }
};

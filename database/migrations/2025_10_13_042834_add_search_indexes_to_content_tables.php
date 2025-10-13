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
        // Add indexes to phrases table for search performance
        Schema::table('phrases', function (Blueprint $table) {
            $table->index('japanese');
            $table->index('romaji');
            $table->index('english');
        });

        // Add indexes to dialogues table for search performance
        Schema::table('dialogues', function (Blueprint $table) {
            $table->index('title');
        });

        // Add indexes to drills table for search performance
        Schema::table('drills', function (Blueprint $table) {
            $table->index('title');
            $table->index('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop indexes from phrases table
        Schema::table('phrases', function (Blueprint $table) {
            $table->dropIndex(['japanese']);
            $table->dropIndex(['romaji']);
            $table->dropIndex(['english']);
        });

        // Drop indexes from dialogues table
        Schema::table('dialogues', function (Blueprint $table) {
            $table->dropIndex(['title']);
        });

        // Drop indexes from drills table
        Schema::table('drills', function (Blueprint $table) {
            $table->dropIndex(['title']);
            $table->dropIndex(['type']);
        });
    }
};

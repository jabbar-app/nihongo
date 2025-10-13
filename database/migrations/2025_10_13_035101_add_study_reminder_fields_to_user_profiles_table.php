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
        Schema::table('user_profiles', function (Blueprint $table) {
            $table->time('study_reminder_time')->nullable()->after('cards_per_day_goal');
            $table->boolean('study_reminders_enabled')->default(false)->after('study_reminder_time');
            $table->boolean('notification_permission_requested')->default(false)->after('study_reminders_enabled');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_profiles', function (Blueprint $table) {
            $table->dropColumn(['study_reminder_time', 'study_reminders_enabled', 'notification_permission_requested']);
        });
    }
};

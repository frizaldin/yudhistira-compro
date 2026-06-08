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
        Schema::table('event_teacher_hubs', function (Blueprint $table) {
            $table->string('completion_type')->default('quiz')->after('link_meeting');
            $table->string('completion_token')->nullable()->unique()->after('completion_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('event_teacher_hubs', function (Blueprint $table) {
            $table->dropColumn(['completion_type', 'completion_token']);
        });
    }
};

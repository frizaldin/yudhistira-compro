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
        Schema::table('blog_teacher_hubs', function (Blueprint $table) {
            $table->string('nama', 255)->nullable()->after('name');
            $table->text('pratinjau')->nullable()->after('overview');
            $table->longText('deskripsi')->nullable()->after('description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('blog_teacher_hubs', function (Blueprint $table) {
            $table->dropColumn(['nama', 'pratinjau', 'deskripsi']);
        });
    }
};

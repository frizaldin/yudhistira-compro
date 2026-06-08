<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('request_books', function (Blueprint $table) {
            $table->string('book_file')->nullable()->after('code_book');
        });
    }

    public function down(): void
    {
        Schema::table('request_books', function (Blueprint $table) {
            $table->dropColumn('book_file');
        });
    }
};

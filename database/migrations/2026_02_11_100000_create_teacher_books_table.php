<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teacher_books', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('teachers')->onDelete('cascade');
            $table->string('code', 100);
            $table->unsignedBigInteger('book_id')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teacher_books');
    }
};

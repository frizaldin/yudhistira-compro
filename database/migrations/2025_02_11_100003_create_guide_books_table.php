<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('guide_books', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('category_guide_books')->onDelete('cascade');
            $table->text('thumbnail')->nullable();
            $table->string('title', 255);
            $table->string('judul', 255)->nullable();
            $table->text('file')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('guide_books');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('category_guide_books', function (Blueprint $table) {
            $table->id();
            $table->string('title', 200);
            $table->string('judul', 200)->nullable();
            $table->text('url')->nullable();
            $table->integer('order')->nullable();
            $table->enum('visible', ['yes', 'no'])->default('yes');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('category_guide_books');
    }
};

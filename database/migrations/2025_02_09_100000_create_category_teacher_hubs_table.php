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
        Schema::create('category_teacher_hubs', function (Blueprint $table) {
            $table->id();
            $table->string('title', 200);
            $table->string('judul', 200);
            $table->text('file')->nullable();
            $table->text('url')->nullable();
            $table->integer('order')->nullable();
            $table->enum('visible', ['yes', 'no'])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('category_teacher_hubs');
    }
};

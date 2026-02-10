<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teacher_rewards', function (Blueprint $table) {
            $table->id();
            $table->text('photo')->nullable();
            $table->string('title', 255);
            $table->string('judul', 255)->nullable();
            $table->string('point', 100)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teacher_rewards');
    }
};

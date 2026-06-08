<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_teacher_hub_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('event_teacher_hubs')->onDelete('cascade');
            $table->string('title', 255);
            $table->string('judul', 255)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_teacher_hub_questions');
    }
};

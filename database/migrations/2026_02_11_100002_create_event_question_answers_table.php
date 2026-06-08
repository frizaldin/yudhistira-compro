<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_question_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('event_teacher_hubs')->onDelete('cascade');
            $table->foreignId('question_id')->constrained('event_teacher_hub_questions')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('teachers')->onDelete('cascade');
            $table->text('answer');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_question_answers');
    }
};

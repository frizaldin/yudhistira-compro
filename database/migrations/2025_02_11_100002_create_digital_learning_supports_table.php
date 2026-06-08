<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('digital_learning_supports', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255);
            $table->string('judul', 255)->nullable();
            $table->enum('video_tipe', ['internal', 'external'])->default('internal');
            $table->text('file')->nullable();
            $table->text('embed')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('digital_learning_supports');
    }
};

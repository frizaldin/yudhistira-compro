<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('announcement_teacher_hubs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('category_announcement_teacher_hubs')->onDelete('cascade');
            $table->string('name', 255);
            $table->string('nama', 255)->nullable();
            $table->text('photo')->nullable();
            $table->text('overview')->nullable();
            $table->text('pratinjau')->nullable();
            $table->longText('description')->nullable();
            $table->longText('deskripsi')->nullable();
            $table->text('tags')->nullable();
            $table->text('url')->nullable();
            $table->enum('visible', ['yes', 'no'])->default('no');
            $table->date('date')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('announcement_teacher_hubs');
    }
};

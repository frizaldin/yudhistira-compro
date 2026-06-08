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
        Schema::create('blog_teacher_hubs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('category_teacher_hubs')->onDelete('cascade');
            $table->string('name', 255);
            $table->text('photo')->nullable();
            $table->text('overview')->nullable();
            $table->longText('description')->nullable();
            $table->text('tags')->nullable();
            $table->text('url')->nullable();
            $table->enum('visible', ['yes', 'no'])->default('no');
            $table->date('date')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blog_teacher_hubs');
    }
};

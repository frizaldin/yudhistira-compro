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
        Schema::create('second_benefits', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('digital_product_id')->nullable();
            $table->string('title', 255);
            $table->longText('description');
            $table->string('judul', 255)->nullable();
            $table->longText('deskripsi')->nullable();
            $table->text('file');
            $table->unsignedBigInteger('order');
            $table->enum('visible', ['yes', 'no'])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('second_benefits');
    }
};

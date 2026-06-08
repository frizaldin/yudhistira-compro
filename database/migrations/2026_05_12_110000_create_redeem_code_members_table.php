<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('redeem_code_members', function (Blueprint $table) {
            $table->id();
            $table->string('code', 255)->unique();
            $table->json('serial_code_ebook')->nullable();
            $table->integer('book_id');
            $table->json('book_json')->nullable();
            $table->enum('used', ['yes', 'no'])->default('no');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('redeem_code_members');
    }
};

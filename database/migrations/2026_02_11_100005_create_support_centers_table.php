<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('support_centers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('teachers')->onDelete('cascade');
            $table->string('ticket_number', 50)->unique();
            $table->enum('status', ['new', 'process', 'done'])->default('new');
            $table->string('title');
            $table->string('topic')->nullable();
            $table->text('message');
            $table->dateTime('datetime')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('support_centers');
    }
};

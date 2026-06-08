<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('support_center_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('support_center_id')->constrained('support_centers')->onDelete('cascade');
            $table->string('path'); // path di storage (support_centers/{id}/filename)
            $table->string('original_name')->nullable(); // nama file asli
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('support_center_attachments');
    }
};

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
        Schema::table('configurations', function (Blueprint $table) {
            $table->text('digital_product_judul')->nullable();
            $table->text('digital_product_description')->nullable();
            $table->text('digital_product_deskripsi')->nullable();
            $table->text('service_title')->nullable();
            $table->text('service_judul')->nullable();
            $table->text('service_description')->nullable();
            $table->text('service_deskripsi')->nullable();
            $table->text('contact_title')->nullable();
            $table->text('contact_judul')->nullable();
            $table->text('contact_description')->nullable();
            $table->text('contact_deskripsi')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('configurations', function (Blueprint $table) {
            $table->dropColumn([
                'digital_product_judul',
                'digital_product_description',
                'digital_product_deskripsi',
                'service_title',
                'service_judul',
                'service_description',
                'service_deskripsi',
                'contact_title',
                'contact_judul',
                'contact_description',
                'contact_deskripsi'
            ]);
        });
    }
};


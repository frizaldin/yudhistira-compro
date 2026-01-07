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
        Schema::table('events', function (Blueprint $table) {
            // Add new fields for Indonesian translations
            $table->string('nama', 255)->nullable()->after('name');
            $table->longText('pratinjau')->nullable()->after('overview');
            $table->longText('deskripsi')->nullable()->after('description');

            // Change date from date to datetime
            $table->datetime('date')->nullable()->change();

            // Set default value for visible if not already set
            if (Schema::hasColumn('events', 'visible')) {
                $table->enum('visible', ['yes', 'no'])->default('yes')->change();
            }

            // Remove fields that are not in the new structure
            if (Schema::hasColumn('events', 'time')) {
                $table->dropColumn('time');
            }
            if (Schema::hasColumn('events', 'location')) {
                $table->dropColumn('location');
            }
            if (Schema::hasColumn('events', 'speaker')) {
                $table->dropColumn('speaker');
            }
            if (Schema::hasColumn('events', 'price')) {
                $table->dropColumn('price');
            }
            if (Schema::hasColumn('events', 'price_description')) {
                $table->dropColumn('price_description');
            }
            if (Schema::hasColumn('events', 'link')) {
                $table->dropColumn('link');
            }
            if (Schema::hasColumn('events', 'city')) {
                $table->dropColumn('city');
            }
            if (Schema::hasColumn('events', 'address')) {
                $table->dropColumn('address');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            // Remove new fields
            $table->dropColumn(['nama', 'pratinjau', 'deskripsi']);

            // Add back removed fields
            $table->time('time')->nullable();
            $table->string('location')->nullable();
            $table->string('speaker')->nullable();
            $table->string('price')->nullable();
            $table->text('price_description')->nullable();
            $table->string('link')->nullable();
            $table->string('city')->nullable();
            $table->text('address')->nullable();

            // Revert date to date type
            $table->date('date')->nullable()->change();
        });
    }
};

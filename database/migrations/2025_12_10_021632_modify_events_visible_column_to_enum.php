<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update existing data: 'yes' -> 'publish', 'no' -> 'draft'
        DB::table('events')->where('visible', 'yes')->update(['visible' => 'publish']);
        DB::table('events')->where('visible', 'no')->update(['visible' => 'draft']);
        
        // Modify the column to ENUM
        DB::statement("ALTER TABLE events MODIFY COLUMN visible ENUM('draft', 'pending', 'publish') DEFAULT 'draft'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Update existing data: 'publish' -> 'yes', others -> 'no'
        DB::table('events')->where('visible', 'publish')->update(['visible' => 'yes']);
        DB::table('events')->whereIn('visible', ['draft', 'pending'])->update(['visible' => 'no']);
        
        // Revert to original ENUM('yes', 'no')
        DB::statement("ALTER TABLE events MODIFY COLUMN visible ENUM('yes', 'no') DEFAULT 'no'");
    }
};


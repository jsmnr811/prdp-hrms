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
        // MySQL requires dropping and recreating enum columns
        // First, update any existing records to use a valid status
        DB::table('wfh_timelogs')->whereNotIn('status', ['pending', 'approved', 'rejected', 'completed'])->update(['status' => 'pending']);

        // Change enum to include 'completed'
        DB::statement("ALTER TABLE wfh_timelogs MODIFY COLUMN status ENUM('pending', 'approved', 'rejected', 'completed') DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove 'completed' from enum
        DB::statement("ALTER TABLE wfh_timelogs MODIFY COLUMN status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending'");
    }
};

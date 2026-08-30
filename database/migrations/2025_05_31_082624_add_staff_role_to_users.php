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
        // Alter the role enum to include 'staff' as a valid value
        DB::statement("ALTER TABLE `users` MODIFY `role` ENUM('student','teacher','finance','library','administration','school','saasAdmin','staff') NOT NULL DEFAULT 'student'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to the original enum without 'staff'
        DB::statement("ALTER TABLE `users` MODIFY `role` ENUM('student','teacher','finance','library','administration','school','saasAdmin') NOT NULL DEFAULT 'student'");
    }
};

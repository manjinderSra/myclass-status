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
        // Ensure we have the correct structure for user_roles table
        // First, check if the table exists
        if (Schema::hasTable('user_roles')) {
            // Drop and recreate the table to ensure it has the correct structure
            Schema::dropIfExists('user_roles');
        }
        
        // Create the table with the correct structure
        Schema::create('user_roles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('role_id')->constrained('roles')->onDelete('cascade');
            $table->timestamps();
            
            // Ensure no duplicate user-role combinations
            $table->unique(['user_id', 'role_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No need to do anything in down since we're just ensuring correct structure
    }
};

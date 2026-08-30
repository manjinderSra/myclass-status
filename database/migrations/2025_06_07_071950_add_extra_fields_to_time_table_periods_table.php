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
        Schema::table('time_table_periods', function (Blueprint $table) {
            // Make subject and teacher nullable
            $table->string('subject')->nullable()->change();
            $table->string('teacher')->nullable()->change();
            
            // Add name field for extra periods like breaks, lunch, etc.
            $table->string('name')->nullable()->after('day');
            
            // Add period_type to distinguish between regular and extra periods
            $table->enum('period_type', ['regular', 'extra'])->default('regular')->after('time_to');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('time_table_periods', function (Blueprint $table) {
            $table->dropColumn('name');
            $table->dropColumn('period_type');
            $table->string('subject')->nullable(false)->change();
            $table->string('teacher')->nullable(false)->change();
        });
    }
};

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
        Schema::table('time_tables', function (Blueprint $table) {
            // Make start_time and duration fields nullable
            $table->time('start_time')->nullable()->change();
            $table->integer('duration')->nullable()->comment('Duration in minutes')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('time_tables', function (Blueprint $table) {
            // Revert changes - make fields required again
            $table->time('start_time')->nullable(false)->change();
            $table->integer('duration')->nullable(false)->comment('Duration in minutes')->change();
        });
    }
};

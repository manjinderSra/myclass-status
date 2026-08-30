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
        Schema::create('time_table_periods', function (Blueprint $table) {
            $table->id();
            $table->foreignId('timetable_id')->constrained('time_tables')->onDelete('cascade');
            $table->string('day');
            $table->string('subject');
            $table->string('teacher');
            $table->time('time_from');
            $table->time('time_to');
            $table->timestamps();
            
            // Add indexes for performance
            $table->index(['timetable_id', 'day']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('time_table_periods');
    }
};

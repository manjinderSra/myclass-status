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
        Schema::create('student_attendance', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->onDelete('cascade');
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->string('class_name');
            $table->foreignId('section_id')->constrained('sections')->onDelete('cascade');
            $table->date('attendance_date');
            $table->enum('status', ['present', 'absent', 'late', 'half_day', 'leave']);
            $table->text('remarks')->nullable();
            $table->foreignId('teacher_id')->nullable()->constrained('teachers')->onDelete('set null');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
            
            // Add indexes for performance
            $table->index(['school_id', 'student_id', 'attendance_date']);
            $table->index(['class_name', 'section_id']);
            $table->index('status');
            $table->index('teacher_id');
            
            // Prevent duplicate attendance records for the same student on the same day
            $table->unique(['student_id', 'attendance_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_attendance');
    }
}; 
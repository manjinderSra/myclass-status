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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->onDelete('cascade');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('academic_number')->unique(); // Auto-generated academic number
            $table->string('student_id')->unique(); // Auto-generated student ID like STU-3432wd
            $table->string('admission_number')->nullable();
            $table->string('roll_number')->nullable();
            $table->foreignId('class_id')->nullable()->constrained('school_classes')->onDelete('set null');
            $table->foreignId('section_id')->nullable()->constrained()->onDelete('set null');
            $table->string('gender')->nullable();
            $table->date('dob')->nullable();
            $table->string('blood_group')->nullable();
            $table->string('religion')->nullable();
            $table->string('house')->nullable();
            $table->string('category')->nullable();
            $table->string('primary_contact')->nullable();
            $table->date('admission_date')->nullable();
            $table->string('academic_year')->nullable();
            $table->string('status')->default('active');
            $table->string('profile_image')->nullable();
            $table->foreignId('parent_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
}; 
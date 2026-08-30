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
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools');
            $table->string('employee_id')->unique();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('gender');
            $table->string('primary_contact');
            $table->string('subject')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->date('date_of_joining')->nullable();
            $table->string('blood_group')->nullable();
            $table->string('father_name')->nullable();
            $table->string('mother_name')->nullable();
            $table->string('marital_status')->nullable();
            $table->text('languages_known')->nullable();
            $table->string('qualification')->nullable();
            $table->string('work_experience')->nullable();
            $table->string('previous_school')->nullable();
            $table->string('previous_school_address')->nullable();
            $table->string('previous_school_phone')->nullable();
            $table->string('pan_number')->nullable(); // PAN number or ID number
            $table->string('status')->default('active');
            $table->text('notes')->nullable();
            
            // Address information
            $table->text('current_address')->nullable();
            $table->text('permanent_address')->nullable();
            
            // Payroll information
            $table->string('epf_no')->nullable();
            $table->decimal('basic_salary', 10, 2)->nullable();
            $table->string('contract_type')->nullable();
            $table->string('work_shift')->nullable();
            $table->string('work_location')->nullable();
            $table->date('date_of_leaving')->nullable();
            
            // Leave information
            $table->integer('medical_leaves')->nullable();
            $table->integer('casual_leaves')->nullable();
            $table->integer('maternity_leaves')->nullable();
            $table->integer('sick_leaves')->nullable();
            
            // Bank details
            $table->string('bank_name')->nullable();
            $table->string('branch')->nullable();
            $table->string('ifsc_number')->nullable();
            $table->text('other_information')->nullable();
            
            // Transport information
            $table->boolean('transport_enabled')->default(false);
            $table->foreignId('pickup_point_id')->nullable()->constrained('pickup_points')->nullOnDelete();
            
            // Hostel information
            $table->boolean('hostel_enabled')->default(false);
            $table->foreignId('hostel_id')->nullable()->constrained('hostels')->nullOnDelete();
            $table->foreignId('room_id')->nullable()->constrained('hostel_rooms')->nullOnDelete();
            
            // Document paths
            $table->string('profile_image')->nullable();
            $table->string('medical_condition_document')->nullable();
            $table->string('transfer_certificate_document')->nullable();
            
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teachers');
    }
};

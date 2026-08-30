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
        Schema::table('students', function (Blueprint $table) {
            // Father information
            $table->string('father_name')->nullable();
            $table->string('father_email')->nullable();
            $table->string('father_phone_number')->nullable();
            $table->string('father_occupation')->nullable();
            $table->string('father_profile_image')->nullable();
            
            // Mother information
            $table->string('mother_name')->nullable();
            $table->string('mother_email')->nullable();
            $table->string('mother_phone_number')->nullable();
            $table->string('mother_occupation')->nullable();
            $table->string('mother_profile_image')->nullable();
            
            // Guardian information
            $table->string('guardian_type')->nullable();
            $table->string('guardian_name')->nullable();
            $table->string('guardian_relation')->nullable();
            $table->string('guardian_email')->nullable();
            $table->string('guardian_phone_number')->nullable();
            $table->string('guardian_occupation')->nullable();
            $table->text('guardian_address')->nullable();
            $table->string('guardian_profile_image')->nullable();
            
            // Additional fields from the model
            $table->string('mother_tongue')->nullable();
            $table->json('languages_known')->nullable();
            $table->text('current_address')->nullable();
            $table->text('permanent_address')->nullable();
            $table->boolean('transport_enabled')->default(false);
            $table->unsignedBigInteger('pickup_point_id')->nullable();
            $table->boolean('hostel_enabled')->default(false);
            $table->unsignedBigInteger('hostel_id')->nullable();
            $table->unsignedBigInteger('room_id')->nullable();
            $table->string('medical_condition_document')->nullable();
            $table->string('transfer_certificate_document')->nullable();
            $table->string('medical_condition_status')->nullable();
            $table->json('allergies')->nullable();
            $table->json('medications')->nullable();
            $table->string('previous_school_name')->nullable();
            $table->text('previous_school_address')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('branch')->nullable();
            $table->string('ifsc_number')->nullable();
            $table->text('other_information')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            // Father information
            $table->dropColumn([
                'father_name', 'father_email', 'father_phone_number', 
                'father_occupation', 'father_profile_image'
            ]);
            
            // Mother information
            $table->dropColumn([
                'mother_name', 'mother_email', 'mother_phone_number', 
                'mother_occupation', 'mother_profile_image'
            ]);
            
            // Guardian information
            $table->dropColumn([
                'guardian_type', 'guardian_name', 'guardian_relation', 
                'guardian_email', 'guardian_phone_number', 'guardian_occupation', 
                'guardian_address', 'guardian_profile_image'
            ]);
            
            // Additional fields
            $table->dropColumn([
                'mother_tongue', 'languages_known', 'current_address', 
                'permanent_address', 'transport_enabled', 'pickup_point_id', 
                'hostel_enabled', 'hostel_id', 'room_id', 'medical_condition_document', 
                'transfer_certificate_document', 'medical_condition_status', 
                'allergies', 'medications', 'previous_school_name', 
                'previous_school_address', 'bank_name', 'branch', 
                'ifsc_number', 'other_information'
            ]);
        });
    }
};

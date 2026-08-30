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
        Schema::create('hostel_rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->onDelete('cascade');
            $table->foreignId('hostel_id')->constrained('hostels')->onDelete('cascade');
            $table->foreignId('room_type_id')->nullable()->constrained('hostel_room_types')->onDelete('set null');
            $table->string('room_number');
            $table->integer('beds')->default(1);
            $table->text('description')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
            
            // Add unique constraint for school_id, hostel_id and room_number combination
            $table->unique(['school_id', 'hostel_id', 'room_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hostel_rooms');
    }
};

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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->onDelete('cascade');
            $table->string('vehicle_no');
            $table->string('vehicle_model');
            $table->string('made_year');
            $table->string('registration_no')->unique();
            $table->string('chassis_no')->unique();
            $table->integer('seat_capacity');
            $table->string('gps_tracking_id')->nullable();
            $table->foreignId('driver_id')->nullable()->constrained('vehicle_drivers')->nullOnDelete();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};

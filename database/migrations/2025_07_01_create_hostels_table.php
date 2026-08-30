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
        Schema::create('hostels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->enum('type', ['Boys', 'Girls', 'Co-ed'])->default('Boys');
            $table->text('address')->nullable();
            $table->integer('intake')->default(0); // Total capacity
            $table->text('description')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
            
            // Add unique constraint for school_id and name combination
            $table->unique(['school_id', 'name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hostels');
    }
}; 
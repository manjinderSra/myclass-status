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
        Schema::create('school_media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('type', ['photo', 'video']);
            $table->string('file_path');
            $table->string('thumbnail_path')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->string('category')->default('general');
            $table->boolean('is_featured')->default(false);
            $table->timestamps();
            
            // Add indexes for better performance
            $table->index(['school_id', 'type']);
            $table->index(['school_id', 'category']);
            $table->index(['school_id', 'is_featured']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('school_media');
    }
};

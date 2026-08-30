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
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            // Link to the feature this permission is based on
            $table->foreignId('feature_id')->constrained('features')->onDelete('cascade');
            // Permission type: view, create, edit, delete, etc.
            $table->string('action')->default('view');
            $table->timestamps();
            
            // Make sure we don't have duplicate feature-action combinations
            $table->unique(['feature_id', 'action']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permissions');
    }
};

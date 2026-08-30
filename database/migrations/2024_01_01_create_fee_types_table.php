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
        if (Schema::hasTable('fee_types')) {
            return;
        }

        Schema::create('fee_types', function (Blueprint $table) {
            $table->id();
            $table->string('unique_id', 10)->unique();
            $table->foreignId('school_id')->constrained()->onDelete('cascade');
            $table->foreignId('fee_group_id')->constrained('fee_groups')->onDelete('restrict');
            $table->string('name');
            $table->string('fees_code');
            $table->text('description')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fee_types');
    }
};

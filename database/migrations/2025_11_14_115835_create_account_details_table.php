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
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('school_id')->index();

            $table->string('account_number')->nullable();
            $table->string('ifsc')->nullable();
            $table->string('name')->nullable(); 
            $table->string('upi_id')->nullable();
            $table->text('note')->nullable();

            $table->timestamps();

            // If schools table exists
            $table->foreign('school_id')
                ->references('id')
                ->on('schools')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};

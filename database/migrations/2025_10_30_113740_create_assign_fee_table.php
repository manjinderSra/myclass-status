<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assign_fee', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('fee_group_id');
            $table->unsignedBigInteger('fee_type_id');
            $table->unsignedBigInteger('fee_master_id');
            $table->unsignedBigInteger('student_id');
            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assign_fee');
    }
};

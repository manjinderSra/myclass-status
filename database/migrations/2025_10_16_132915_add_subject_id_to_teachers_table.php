<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('teachers', function (Blueprint $table) {
            // Add subject_id as unsignedBigInteger before the 'subject' column
            $table->unsignedBigInteger('subject_id')->nullable()->before('subject');
        });
    }

    public function down(): void
    {
        Schema::table('teachers', function (Blueprint $table) {
            // Remove subject_id if we rollback
            $table->dropColumn('subject_id');
        });
    }
};

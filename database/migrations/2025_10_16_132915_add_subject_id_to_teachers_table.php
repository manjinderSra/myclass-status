<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('teachers') || Schema::hasColumn('teachers', 'subject_id')) {
            return;
        }

        Schema::table('teachers', function (Blueprint $table) {
            $table->unsignedBigInteger('subject_id')->nullable();
        });
    }

    public function down(): void
    {
        // The earlier subject_id migration owns this column and its foreign key.
    }
};

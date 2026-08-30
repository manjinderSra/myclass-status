<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('teachers') || Schema::hasColumn('teachers', 'subject')) {
            return;
        }

        Schema::table('teachers', function (Blueprint $table) {
            $table->string('subject', 255)->nullable();
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('teachers') || !Schema::hasColumn('teachers', 'subject')) {
            return;
        }

        Schema::table('teachers', function (Blueprint $table) {
            // Drop the column if rollback
            $table->dropColumn('subject');
        });
    }
};

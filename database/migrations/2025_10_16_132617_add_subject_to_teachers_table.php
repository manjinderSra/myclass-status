<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('teachers', function (Blueprint $table) {
            // Add the column back
            $table->string('subject', 255)->nullable()->after('some_column'); // replace 'some_column' with the column after which you want to add it
        });
    }

    public function down(): void
    {
        Schema::table('teachers', function (Blueprint $table) {
            // Drop the column if rollback
            $table->dropColumn('subject');
        });
    }
};

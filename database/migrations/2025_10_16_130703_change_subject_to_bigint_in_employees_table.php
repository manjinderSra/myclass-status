<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Teacher;
use App\Models\Subject;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('teachers')) {
            return;
        }

        // Keep the legacy text subject and add the relation used by the app.
        Schema::table('teachers', function (Blueprint $table) {
            if (!Schema::hasColumn('teachers', 'subject_id')) {
                $table->unsignedBigInteger('subject_id')->nullable()->after('subject');
            }
        });
    }

    public function down(): void
    {
        // This migration only protects the existing teacher subject columns.
    }
};

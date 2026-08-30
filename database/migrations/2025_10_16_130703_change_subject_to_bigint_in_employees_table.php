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
        // Step 1: Add new nullable subject_id column if it doesn't exist
        Schema::table('teachers', function (Blueprint $table) {
            if (!Schema::hasColumn('teachers', 'subject_id')) {
                $table->unsignedBigInteger('subject_id')->nullable()->after('subject');
            }
        });

        // Step 2: Map existing string subject to subject_id
        $teachers = Teacher::all();
        foreach ($teachers as $teacher) {
            $subject = Subject::where('name', $teacher->subject)->first();
            if ($subject) {
                $teacher->subject_id = $subject->id;
                $teacher->save();
            }
        }

        // Step 3: Drop old varchar subject column if it exists
        Schema::table('teachers', function (Blueprint $table) {
            if (Schema::hasColumn('teachers', 'subject')) {
                $table->dropColumn('subject');
            }
        });

        // Step 4: Rename subject_id to subject if it exists
        Schema::table('teachers', function (Blueprint $table) {
            if (Schema::hasColumn('teachers', 'subject_id')) {
                $table->renameColumn('subject_id', 'subject');
            }
        });
    }

    public function down(): void
    {
        // Step 1: Add old varchar column back if it doesn't exist
        Schema::table('teachers', function (Blueprint $table) {
            if (!Schema::hasColumn('teachers', 'subject')) {
                $table->string('subject', 255)->after('subject');
            }
        });

        // Step 2: Copy back names from subjects table
        $teachers = Teacher::all();
        foreach ($teachers as $teacher) {
            $subject = Subject::find($teacher->subject);
            if ($subject) {
                $teacher->subject = $subject->name;
                $teacher->save();
            }
        }

        // Step 3: Drop bigint subject column if exists
        Schema::table('teachers', function (Blueprint $table) {
            if (Schema::hasColumn('teachers', 'subject')) {
                $table->dropColumn('subject');
            }
        });

        // Step 4: Rename back to original (optional)
        // No need if column is already restored
    }
};

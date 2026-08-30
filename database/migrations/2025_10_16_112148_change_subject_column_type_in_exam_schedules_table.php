<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\ExamSchedule;
use App\Models\Subject;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('exam_schedules')) {
            return;
        }

        // Step 1: Add new nullable subject_id column
        Schema::table('exam_schedules', function (Blueprint $table) {
            $table->unsignedBigInteger('subject_id')->nullable()->after('section');
        });

        // Step 2: Map existing string subject to subject_id
        $schedules = ExamSchedule::all();
        foreach ($schedules as $schedule) {
            $subject = Subject::where('name', $schedule->subject)->first();
            if ($subject) {
                $schedule->subject_id = $subject->id;
                $schedule->save();
            }
        }

        // Step 3: Drop old string subject column
        Schema::table('exam_schedules', function (Blueprint $table) {
            $table->dropColumn('subject');
        });

        // Step 4: Rename subject_id to subject
        Schema::table('exam_schedules', function (Blueprint $table) {
            $table->renameColumn('subject_id', 'subject');
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('exam_schedules')) {
            return;
        }

        // Reverse: Add old varchar column
        Schema::table('exam_schedules', function (Blueprint $table) {
            $table->string('subject', 255)->after('section');
        });

        // Copy back names from subjects table
        $schedules = ExamSchedule::all();
        foreach ($schedules as $schedule) {
            $subject = Subject::find($schedule->subject);
            if ($subject) {
                $schedule->subject = $subject->name;
                $schedule->save();
            }
        }

        // Drop bigint subject column
        Schema::table('exam_schedules', function (Blueprint $table) {
            $table->dropColumn('subject');
        });

        // Rename back to original
        Schema::table('exam_schedules', function (Blueprint $table) {
            $table->renameColumn('subject', 'subject');
        });
    }
};

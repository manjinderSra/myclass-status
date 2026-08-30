<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UpdateStudentRollNumbers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Get all students grouped by class and section
        $students = DB::table('students')
            ->whereNull('deleted_at')
            ->orderBy('admission_date')
            ->orderBy('id')
            ->get();

        $rollNumbers = [];
        
        // Generate roll numbers
        foreach ($students as $student) {
            $key = $student->class_id . '-' . $student->section_id;
            if (!isset($rollNumbers[$key])) {
                $rollNumbers[$key] = 1;
            }
            
            // Update roll number
            DB::table('students')
                ->where('id', $student->id)
                ->update(['roll_number' => $rollNumbers[$key]]);
            
            $rollNumbers[$key]++;
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('students')->update(['roll_number' => null]);
    }
} 
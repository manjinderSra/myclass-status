<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('teachers', function (Blueprint $table) {
            // Add subject_id column
            $table->unsignedBigInteger('subject_id')->nullable()->after('subject');
            
            // Add foreign key constraint
            $table->foreign('subject_id')
                  ->references('id')
                  ->on('subjects')
                  ->onDelete('set null');
        });
        
        // Transfer data from the subject text field to subject_id where possible
        // This assumes that the subject field contains the name of the subject
        // and there is a subjects table with a name column
        $this->transferSubjectData();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('teachers', function (Blueprint $table) {
            // Drop foreign key constraint
            $table->dropForeign(['subject_id']);
            
            // Drop subject_id column
            $table->dropColumn('subject_id');
        });
    }
    
    /**
     * Transfer data from subject text field to subject_id
     */
    private function transferSubjectData()
    {
        // Get all teachers
        $teachers = DB::table('teachers')->get();
        
        foreach ($teachers as $teacher) {
            if (!empty($teacher->subject)) {
                // Find the subject by name
                $subject = DB::table('subjects')
                    ->where('name', 'like', '%' . $teacher->subject . '%')
                    ->first();
                    
                if ($subject) {
                    // Update the teacher with the subject_id
                    DB::table('teachers')
                        ->where('id', $teacher->id)
                        ->update(['subject_id' => $subject->id]);
                }
            }
        }
    }
};

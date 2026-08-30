<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ExamSchedule;
use Carbon\Carbon;

class UpdateExamStatus extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'exam:update-status';

    /**
     * The console command description.
     */
    protected $description = 'Update exam schedule status based on exam date';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = Carbon::today();

        // Fetch all exams with exam_date < today, that are NOT canceled or already completed
        $schedules = ExamSchedule::whereDate('exam_date', '<', $today)
            ->whereNotIn('status', ['Completed', 'Canceled'])
            ->get();

        $updatedCount = 0;

        foreach ($schedules as $schedule) {
            $schedule->update(['status' => 'Completed']);
            $updatedCount++;
        }

        $this->info("✅ $updatedCount exam status(es) updated successfully!");
    }
}

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\FeeGroup;
use App\Models\School;
use Illuminate\Support\Facades\DB;

class CreateInitialFeeGroups extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:initial-fee-groups {school_id? : The ID of the school to create fee groups for}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create initial fee groups for testing';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Get the school ID from the argument or prompt for it
        $schoolId = $this->argument('school_id');
        
        if (!$schoolId) {
            $schools = School::all();
            
            if ($schools->isEmpty()) {
                $this->error('No schools found in the database!');
                return 1;
            }
            
            $this->info('Available schools:');
            $schools->each(function ($school, $key) {
                $this->line(" [{$school->id}] {$school->name}");
            });
            
            $schoolId = $this->ask('Enter the school ID to create fee groups for:');
        }
        
        // Verify the school exists
        $school = School::find($schoolId);
        if (!$school) {
            $this->error("School with ID {$schoolId} not found!");
            return 1;
        }
        
        $this->info("Creating fee groups for school: {$school->name} (ID: {$school->id})");
        
        // Define the fee groups to create
        $feeGroups = [
            [
                'name' => 'Tuition Fees',
                'description' => 'Regular tuition fees',
                'status' => true,
            ],
            [
                'name' => 'Monthly Fees',
                'description' => 'Monthly recurring fees',
                'status' => true,
            ],
            [
                'name' => 'Transportation Fees',
                'description' => 'Fees for school transportation',
                'status' => true,
            ],
            [
                'name' => 'Hostel Fees',
                'description' => 'Fees for hostel accommodation',
                'status' => true,
            ],
            [
                'name' => 'Library Fees',
                'description' => 'Fees for library usage',
                'status' => true,
            ],
            [
                'name' => 'Laboratory Fees',
                'description' => 'Fees for laboratory usage',
                'status' => true,
            ],
            [
                'name' => 'Examination Fees',
                'description' => 'Fees for examinations',
                'status' => true,
            ],
            [
                'name' => 'Annual Fees',
                'description' => 'Annual one-time fees',
                'status' => true,
            ],
            [
                'name' => 'Admission Fees',
                'description' => 'One-time admission fees',
                'status' => true,
            ],
            [
                'name' => 'Sports Fees',
                'description' => 'Fees for sports activities',
                'status' => true,
            ],
        ];
        
        $bar = $this->output->createProgressBar(count($feeGroups));
        $bar->start();
        
        $createdCount = 0;
        $skippedCount = 0;
        
        DB::beginTransaction();
        
        try {
            foreach ($feeGroups as $groupData) {
                // Check if the fee group already exists for this school
                $exists = FeeGroup::where('school_id', $schoolId)
                    ->where('name', $groupData['name'])
                    ->exists();
                
                if ($exists) {
                    $skippedCount++;
                } else {
                    FeeGroup::create([
                        'school_id' => $schoolId,
                        'name' => $groupData['name'],
                        'description' => $groupData['description'],
                        'status' => $groupData['status'],
                    ]);
                    
                    $createdCount++;
                }
                
                $bar->advance();
            }
            
            DB::commit();
            
            $bar->finish();
            $this->newLine(2);
            
            $this->info("Fee groups created: {$createdCount}");
            $this->info("Fee groups skipped (already exist): {$skippedCount}");
            
            return 0;
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            $this->newLine(2);
            $this->error("Error creating fee groups: {$e->getMessage()}");
            
            return 1;
        }
    }
}

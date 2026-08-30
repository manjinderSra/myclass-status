<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\FeeGroup;
use App\Models\FeeType;
use App\Models\School;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CreateTestFeeTypes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:test-fee-types {school_id? : The ID of the school to create fee types for}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create test fee types for each fee group';

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
            
            $schoolId = $this->ask('Enter the school ID to create fee types for:');
        }
        
        // Verify the school exists
        $school = School::find($schoolId);
        if (!$school) {
            $this->error("School with ID {$schoolId} not found!");
            return 1;
        }
        
        $this->info("Creating fee types for school: {$school->name} (ID: {$school->id})");
        
        // Get all fee groups for this school
        $feeGroups = FeeGroup::where('school_id', $schoolId)->get();
        
        if ($feeGroups->isEmpty()) {
            $this->error("No fee groups found for this school. Please create fee groups first.");
            return 1;
        }
        
        $this->info("Found {$feeGroups->count()} fee groups");
        
        // Define sample fee types for each group
        $sampleTypes = [
            'Tuition Fees' => [
                'Monthly Tuition Fee',
                'Term Tuition Fee',
                'Annual Tuition Fee',
                'Special Subject Fee',
            ],
            'Monthly Fees' => [
                'Monthly Development Fee',
                'Monthly Activity Fee',
                'Monthly Computer Lab Fee',
                'Monthly Smart Class Fee',
            ],
            'Transportation Fees' => [
                'Bus Fee - Zone 1',
                'Bus Fee - Zone 2',
                'Bus Fee - Zone 3',
                'Transportation Maintenance',
            ],
            'Hostel Fees' => [
                'Hostel Room Fee',
                'Hostel Mess Fee',
                'Hostel Maintenance',
                'Hostel Utility Fee',
            ],
            'Library Fees' => [
                'Library Access Fee',
                'Book Issue Fee',
                'Library Maintenance',
                'E-Library Subscription',
            ],
            'Laboratory Fees' => [
                'Science Lab Fee',
                'Computer Lab Fee',
                'Language Lab Fee',
                'Equipment Usage Fee',
            ],
            'Examination Fees' => [
                'Mid-Term Exam Fee',
                'Final Exam Fee',
                'Practical Exam Fee',
                'Special Exam Fee',
            ],
            'Annual Fees' => [
                'Annual Development Fee',
                'Annual Maintenance Fee',
                'Annual Sports Fee',
                'Annual Cultural Fee',
            ],
            'Admission Fees' => [
                'New Admission Fee',
                'Registration Fee',
                'Admission Processing Fee',
                'Document Verification Fee',
            ],
            'Sports Fees' => [
                'Sports Equipment Fee',
                'Sports Coaching Fee',
                'Sports Ground Maintenance',
                'Sports Competition Fee',
            ],
        ];
        
        $totalCreated = 0;
        $totalSkipped = 0;
        
        DB::beginTransaction();
        
        try {
            foreach ($feeGroups as $feeGroup) {
                $this->info("Processing fee group: {$feeGroup->name}");
                
                // Get sample types for this group, or use generic ones if not defined
                $typeNames = $sampleTypes[$feeGroup->name] ?? [
                    "{$feeGroup->name} - Type 1",
                    "{$feeGroup->name} - Type 2",
                    "{$feeGroup->name} - Type 3",
                    "{$feeGroup->name} - Type 4",
                ];
                
                $bar = $this->output->createProgressBar(count($typeNames));
                $bar->start();
                
                $createdForGroup = 0;
                $skippedForGroup = 0;
                
                foreach ($typeNames as $typeName) {
                    // Check if this fee type already exists
                    $exists = FeeType::where('school_id', $schoolId)
                        ->where('fee_group_id', $feeGroup->id)
                        ->where('name', $typeName)
                        ->exists();
                    
                    if ($exists) {
                        $skippedForGroup++;
                        $totalSkipped++;
                    } else {
                        // Generate a unique ID for the fee type (Format: FT12345)
                        $uniqueId = 'FT' . rand(10000, 99999);
                        while (FeeType::where('unique_id', $uniqueId)->exists()) {
                            $uniqueId = 'FT' . rand(10000, 99999);
                        }
                        
                        // Create the fee type
                        FeeType::create([
                            'unique_id' => $uniqueId,
                            'school_id' => $schoolId,
                            'fee_group_id' => $feeGroup->id,
                            'name' => $typeName,
                            'fees_code' => Str::slug($typeName),
                            'description' => "Sample {$typeName} for testing purposes",
                            'status' => true,
                        ]);
                        
                        $createdForGroup++;
                        $totalCreated++;
                    }
                    
                    $bar->advance();
                }
                
                $bar->finish();
                $this->newLine();
                $this->info("  Created: {$createdForGroup}, Skipped: {$skippedForGroup}");
            }
            
            DB::commit();
            
            $this->newLine();
            $this->info("Total fee types created: {$totalCreated}");
            $this->info("Total fee types skipped (already exist): {$totalSkipped}");
            
            return 0;
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            $this->newLine();
            $this->error("Error creating fee types: {$e->getMessage()}");
            
            return 1;
        }
    }
}

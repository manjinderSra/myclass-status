<?php

namespace App\Console\Commands;

use App\Models\Feature;
use App\Models\Permission;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CreatePermissionsForFeatures extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permissions:create-from-features {--feature=* : Specific feature codes to create permissions for}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create standard permissions (view, create, edit, delete) for each feature';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $featureCodes = $this->option('feature');
        
        // If no specific features are provided, get all active features
        if (empty($featureCodes)) {
            $features = Feature::where('is_active', true)->get();
            $this->info('Creating permissions for all ' . $features->count() . ' active features.');
        } else {
            $features = Feature::where('is_active', true)
                ->whereIn('code', $featureCodes)
                ->get();
            $this->info('Creating permissions for ' . $features->count() . ' specified features.');
        }
        
        if ($features->isEmpty()) {
            $this->error('No active features found.');
            return 1;
        }
        
        $standardActions = ['view', 'create', 'edit', 'delete'];
        $createdCount = 0;
        $skippedCount = 0;
        
        DB::beginTransaction();
        
        try {
            foreach ($features as $feature) {
                $this->info('Processing feature: ' . $feature->name);
                
                foreach ($standardActions as $action) {
                    $slug = Permission::generateSlug($feature->code, $action);
                    $name = Permission::generateName($feature->name, $action);
                    
                    // Check if the permission already exists
                    $existingPermission = Permission::where('slug', $slug)->first();
                    
                    if ($existingPermission) {
                        $this->line("  - Permission '{$slug}' already exists. Skipping.");
                        $skippedCount++;
                        continue;
                    }
                    
                    // Create the permission
                    Permission::create([
                        'name' => $name,
                        'slug' => $slug,
                        'description' => "{$action} access to {$feature->name}",
                        'feature_id' => $feature->id,
                        'action' => $action
                    ]);
                    
                    $this->line("  - Created permission: {$name} ({$slug})");
                    $createdCount++;
                }
            }
            
            DB::commit();
            
            $this->info("Process completed. Created {$createdCount} permissions, skipped {$skippedCount} existing permissions.");
            return 0;
        } catch (\Exception $e) {
            DB::rollBack();
            
            $this->error('Failed to create permissions: ' . $e->getMessage());
            $this->error($e->getTraceAsString());
            return 1;
        }
    }
}

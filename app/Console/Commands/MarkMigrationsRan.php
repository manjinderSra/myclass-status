<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MarkMigrationsRan extends Command
{
    protected $signature = 'mark:migrations-ran';
    protected $description = 'Mark existing migration tables as ran without running them.';

    public function handle()
    {
        $pendingMigrations = DB::table('migrations')->pluck('migration')->toArray();
        $migrationPath = database_path('migrations');
        $files = scandir($migrationPath);
        $batch = DB::table('migrations')->max('batch') ?? 1;

        foreach ($files as $file) {
            if (str_ends_with($file, '.php')) {
                $migrationName = pathinfo($file, PATHINFO_FILENAME);

                // Skip already-ran migrations
                if (in_array($migrationName, $pendingMigrations)) {
                    continue;
                }

                // Try to guess table name from migration file name
                $matches = [];
                if (preg_match('/create_(.*)_table/', $migrationName, $matches)) {
                    $table = $matches[1];
                    if (Schema::hasTable($table)) {
                        DB::table('migrations')->insert([
                            'migration' => $migrationName,
                            'batch' => $batch,
                        ]);
                        $this->info("✅ Marked {$migrationName} as ran (table {$table} already exists).");
                    }
                }
            }
        }

        $this->info('🎉 All existing tables marked as migrated.');
    }
}

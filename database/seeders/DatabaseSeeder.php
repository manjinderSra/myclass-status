<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Deployments may invoke this seeder automatically. Demo data must be
        // explicitly enabled so a real database is never truncated/populated.
        if (!filter_var(env('SEED_DEMO_DATA', false), FILTER_VALIDATE_BOOL)) {
            $this->command->info('Skipping demo seeders (SEED_DEMO_DATA is false).');
            return;
        }

        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->call([
            // ... other seeders
            TestSubscriptionSeeder::class,
            StudentsAndTeachersSeeder::class,
            StudentLeaveSeeder::class,
            HelpTopicSeeder::class,
            SupportTicketSeeder::class,
        ]);
    }
}

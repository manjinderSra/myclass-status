<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class ProductionAdminSeeder extends Seeder
{
    /**
     * Create or update the production SaaS administrator.
     */
    public function run(): void
    {
        if (!app()->environment('production')) {
            $this->command?->info(
                'Skipping production admin outside the production environment.'
            );

            return;
        }

        $admin = User::updateOrCreate(
            [
                'email' => 'admin@gmail.com',
            ],
            [
                'name' => 'SaaS Administrator',
                'first_name' => 'SaaS',
                'last_name' => 'Administrator',
                'username' => 'saasadmin',
                'password' => 'password@321',
                'role' => 'saasAdmin',
                'school_id' => null,
                'phone' => '9876543210',
                'address' => 'Noida, Uttar Pradesh, India',
                'date_of_birth' => '1990-01-01',
                'gender' => 'male',
                'is_active' => true,
            ]
        );

        if ($admin->email_verified_at === null) {
            $admin->forceFill([
                'email_verified_at' => now(),
            ])->save();
        }

        $this->command?->info(
            $admin->wasRecentlyCreated
                ? 'Production SaaS administrator created successfully.'
                : 'Production SaaS administrator updated successfully.'
        );
    }
}
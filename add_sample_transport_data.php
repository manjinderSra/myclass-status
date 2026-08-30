<?php

// This script adds sample transport data (routes and vehicles)
require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

// First, let's check the current state of the tables
$existingVehicles = DB::table('vehicles')->count();
$existingRoutes = DB::table('route_details')->count();
$existingPickupPoints = DB::table('pickup_points')->count();

// Get first school ID
$school = DB::table('schools')->first();
if (!$school) {
    die("No schools found in the database. Please create a school first.");
}

$schoolId = $school->id;
echo "Using school ID: {$schoolId} ({$school->name})\n\n";

echo "Current database state:\n";
echo "Vehicles: {$existingVehicles}\n";
echo "Routes: {$existingRoutes}\n";
echo "Pickup Points: {$existingPickupPoints}\n\n";

// Temporarily disable foreign key checks
DB::statement('SET FOREIGN_KEY_CHECKS=0;');

// Clear existing data
DB::table('pickup_points')->delete();
DB::table('route_details')->delete();
DB::table('vehicles')->delete();

echo "Tables cleared. Adding new data...\n\n";

// Re-enable foreign key checks
DB::statement('SET FOREIGN_KEY_CHECKS=1;');

// Add sample vehicles
$vehicles = [
    [
        'school_id' => $schoolId,
        'vehicle_no' => 'BUS-001',
        'vehicle_model' => 'Tata Ultra School Bus',
        'made_year' => '2022',
        'registration_no' => 'DL01AB1234',
        'chassis_no' => 'TATA123456789',
        'seat_capacity' => 40,
        'status' => 1,
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'school_id' => $schoolId,
        'vehicle_no' => 'BUS-002',
        'vehicle_model' => 'Ashok Leyland Sunshine',
        'made_year' => '2021',
        'registration_no' => 'DL01CD5678',
        'chassis_no' => 'ASHOK987654321',
        'seat_capacity' => 35,
        'status' => 1,
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'school_id' => $schoolId,
        'vehicle_no' => 'VAN-001',
        'vehicle_model' => 'Force Traveller',
        'made_year' => '2023',
        'registration_no' => 'DL01EF9012',
        'chassis_no' => 'FORCE123789456',
        'seat_capacity' => 15,
        'status' => 1,
        'created_at' => now(),
        'updated_at' => now(),
    ],
];

// Add sample routes
$routes = [
    [
        'school_id' => $schoolId,
        'route_name' => 'North Delhi Route',
        'description' => 'Covering major areas in North Delhi',
        'status' => 1,
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'school_id' => $schoolId,
        'route_name' => 'South Delhi Route',
        'description' => 'Covering major areas in South Delhi',
        'status' => 1,
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'school_id' => $schoolId,
        'route_name' => 'East Delhi Route',
        'description' => 'Covering major areas in East Delhi',
        'status' => 1,
        'created_at' => now(),
        'updated_at' => now(),
    ],
];

// Insert vehicles
try {
    DB::table('vehicles')->insert($vehicles);
    echo "Added " . count($vehicles) . " sample vehicles.\n";
} catch (\Exception $e) {
    echo "Error adding vehicles: " . $e->getMessage() . "\n";
}

// Insert routes
try {
    DB::table('route_details')->insert($routes);
    echo "Added " . count($routes) . " sample routes.\n";
} catch (\Exception $e) {
    echo "Error adding routes: " . $e->getMessage() . "\n";
}

// Add sample pickup points after routes are inserted
$pickupPoints = [
    // North Delhi Route (ID: 1)
    [
        'route_detail_id' => DB::table('route_details')->where('route_name', 'North Delhi Route')->value('id'),
        'name' => 'Model Town',
        'sequence' => 1,
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'route_detail_id' => DB::table('route_details')->where('route_name', 'North Delhi Route')->value('id'),
        'name' => 'Ashok Vihar',
        'sequence' => 2,
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'route_detail_id' => DB::table('route_details')->where('route_name', 'North Delhi Route')->value('id'),
        'name' => 'Pitampura',
        'sequence' => 3,
        'created_at' => now(),
        'updated_at' => now(),
    ],
    
    // South Delhi Route (ID: 2)
    [
        'route_detail_id' => DB::table('route_details')->where('route_name', 'South Delhi Route')->value('id'),
        'name' => 'Saket',
        'sequence' => 1,
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'route_detail_id' => DB::table('route_details')->where('route_name', 'South Delhi Route')->value('id'),
        'name' => 'Malviya Nagar',
        'sequence' => 2,
        'created_at' => now(),
        'updated_at' => now(),
    ],
    
    // East Delhi Route (ID: 3)
    [
        'route_detail_id' => DB::table('route_details')->where('route_name', 'East Delhi Route')->value('id'),
        'name' => 'Laxmi Nagar',
        'sequence' => 1,
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'route_detail_id' => DB::table('route_details')->where('route_name', 'East Delhi Route')->value('id'),
        'name' => 'Mayur Vihar',
        'sequence' => 2,
        'created_at' => now(),
        'updated_at' => now(),
    ],
];

// Insert pickup points
try {
    DB::table('pickup_points')->insert($pickupPoints);
    echo "Added " . count($pickupPoints) . " sample pickup points.\n";
} catch (\Exception $e) {
    echo "Error adding pickup points: " . $e->getMessage() . "\n";
}

// Verify data was added
$newVehicles = DB::table('vehicles')->count();
$newRoutes = DB::table('route_details')->count();
$newPickupPoints = DB::table('pickup_points')->count();

echo "\nVerification:\n";
echo "Vehicles: {$newVehicles}\n";
echo "Routes: {$newRoutes}\n";
echo "Pickup Points: {$newPickupPoints}\n";

echo "\nSample transport data has been added successfully!\n"; 
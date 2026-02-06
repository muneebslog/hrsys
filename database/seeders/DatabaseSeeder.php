<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        $adminRole = Role::firstOrCreate(
        ['name' => 'admin'],
        ['description' => 'System Administrator']
    );
    Role::firstOrCreate(
        ['name' => 'employee'],
        ['description' => "Hospital's Employee Role"]
    );
    
     Role::firstOrCreate(
        ['name' => 'doctor'],
        ['description' => 'Doctor Role  ']
    );

    Role::firstOrCreate(
        ['name' => 'staff'],
        ['description' => 'Staff Role  ']
    );

    Role::firstOrCreate(
        ['name' => 'supervisor'],
        ['description' => 'Ward round supervisor']
    );

    User::firstOrCreate(
        ['email' => 'admin@mmc.com'],
        [
            'name' => 'Admin',
            'password' =>'admin123',
            'email_verified_at' => now(),
            'role_id' => $adminRole->id,
            'is_active' => true,
        ]
    );
    }
}

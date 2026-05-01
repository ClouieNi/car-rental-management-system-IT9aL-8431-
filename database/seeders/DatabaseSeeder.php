<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@carsnibai.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        // Create demo data (cars, suppliers)
        $this->call(DemoSeeder::class);
    }
}

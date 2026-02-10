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
        // Seed categories first
        $this->call([
            CategorySeeder::class,
            ProductSeeder::class,
        ]);

        // Create admin user for Filament
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@quickdraw.com',
        ]);
    }
}

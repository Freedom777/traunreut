<?php

namespace Database\Seeders;

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
        // Seed states (required for cities)
        $this->call(StateSeeder::class);
        
        // Seed cities
        $this->call(CitySeeder::class);
        
        // Seed event types and keywords
        $this->call(EventTypeSeeder::class);
        
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }
}

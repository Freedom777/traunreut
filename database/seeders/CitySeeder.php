<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Importing German cities...');
        
        // Call the import:cities command
        $exitCode = Artisan::call('import:cities', [], $this->command->getOutput());
        
        if ($exitCode === 0) {
            $this->command->info('Cities imported successfully.');
        } else {
            $this->command->error('Failed to import cities.');
        }

        // Add Additional Cities (Austrian & Districts)
        $this->command->info('Seeding additional cities...');
        $cities = [
            // Austrian Cities
            ['name' => 'Tirol', 'zip_code' => '6322', 'state_code' => 'AT', 'is_district' => false],
            ['name' => 'Waidring', 'zip_code' => '6384', 'state_code' => 'AT', 'is_district' => false],
            ['name' => 'Kössen', 'zip_code' => '6345', 'state_code' => 'AT', 'is_district' => false],

            // German Districts / Parts (Bayern)
            ['name' => 'Eisenärzt', 'zip_code' => '83313', 'state_code' => 'BY', 'is_district' => true],
            ['name' => 'Hammer', 'zip_code' => '83313', 'state_code' => 'BY', 'is_district' => true],
            ['name' => 'Rottau', 'zip_code' => '83224', 'state_code' => 'BY', 'is_district' => true],
            ['name' => 'Oberwössen', 'zip_code' => '83246', 'state_code' => 'BY', 'is_district' => true],
            ['name' => 'Tettenhausen', 'zip_code' => '83329', 'state_code' => 'BY', 'is_district' => true],
            ['name' => 'Sondermoning', 'zip_code' => '83365', 'state_code' => 'BY', 'is_district' => true],
            ['name' => 'Stein a.d. Traun', 'zip_code' => '83371', 'state_code' => 'BY', 'is_district' => true],
            ['name' => 'Tengling', 'zip_code' => '83373', 'state_code' => 'BY', 'is_district' => true],
            ['name' => 'Tengling am See', 'zip_code' => '83373', 'state_code' => 'BY', 'is_district' => true],
            
            // St. Leonhard variants
            ['name' => 'St. Leonhard', 'zip_code' => '83365', 'state_code' => 'BY', 'is_district' => true],
            ['name' => 'St. Leonhard am Wonneberg', 'zip_code' => '83365', 'state_code' => 'BY', 'is_district' => true],
            
            // Other Missing
            ['name' => 'Chiemgau', 'zip_code' => '', 'state_code' => 'BY', 'is_district' => true],
            ['name' => 'Herreninsel', 'zip_code' => '83209', 'state_code' => 'BY', 'is_district' => true],
            ['name' => 'Ettenhausen', 'zip_code' => '83259', 'state_code' => 'BY', 'is_district' => true],
        ];

        foreach ($cities as $city) {
            \App\Models\City::upsert(
                $city,
                ['zip_code', 'name'],
                ['state_code', 'is_district']
            );
        }
        $this->command->info('Additional cities seeded successfully.');
    }
}

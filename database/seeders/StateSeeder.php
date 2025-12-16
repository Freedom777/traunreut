<?php

namespace Database\Seeders;

use App\Models\State;
use Illuminate\Database\Seeder;

class StateSeeder extends Seeder
{
    public function run(): void
    {
        $states = [
            ['code' => 'BW', 'name' => 'Baden-Württemberg'],
            ['code' => 'BY', 'name' => 'Bayern'],
            ['code' => 'BE', 'name' => 'Berlin'],
            ['code' => 'BB', 'name' => 'Brandenburg'],
            ['code' => 'HB', 'name' => 'Bremen'],
            ['code' => 'HH', 'name' => 'Hamburg'],
            ['code' => 'HE', 'name' => 'Hessen'],
            ['code' => 'MV', 'name' => 'Mecklenburg-Vorpommern'],
            ['code' => 'NI', 'name' => 'Niedersachsen'],
            ['code' => 'NW', 'name' => 'Nordrhein-Westfalen'],
            ['code' => 'RP', 'name' => 'Rheinland-Pfalz'],
            ['code' => 'SL', 'name' => 'Saarland'],
            ['code' => 'SN', 'name' => 'Sachsen'],
            ['code' => 'ST', 'name' => 'Sachsen-Anhalt'],
            ['code' => 'SH', 'name' => 'Schleswig-Holstein'],
            ['code' => 'TH', 'name' => 'Thüringen'],
            ['code' => 'AT', 'name' => 'Österreich'],
            ['code' => 'UN', 'name' => ''],
        ];

        foreach ($states as $state) {
            State::firstOrCreate(
                ['code' => $state['code']],
                ['name' => $state['name']]
            );
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Seeder;

class TestCitySeeder extends Seeder
{
    public function run(): void
    {
        $cities = [
            ['name' => 'Aach', 'zip_code' => '78267', 'state_code' => 'BW'],
            ['name' => 'Aach', 'zip_code' => '54298', 'state_code' => 'RP'],
            ['name' => 'Traunreut', 'zip_code' => '83301', 'state_code' => 'BY'],
        ];

        foreach ($cities as $city) {
            City::firstOrCreate(
                ['zip_code' => $city['zip_code'], 'name' => $city['name']],
                ['state_code' => $city['state_code']]
            );
        }
    }
}

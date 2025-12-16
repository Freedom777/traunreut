<?php

namespace Database\Seeders;

use App\Models\EventTitle;
use Illuminate\Database\Seeder;

class EventTitleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $file = base_path('csv/event_titles.csv');
        if (!file_exists($file)) {
            return;
        }

        $handle = fopen($file, 'r');
        $header = fgetcsv($handle); // Skip header

        while (($data = fgetcsv($handle)) !== false) {
            if (count($data) < 2) {
                continue;
            }
            
            [$title_de, $title_ru] = $data;

            EventTitle::firstOrCreate(
                ['title_de' => $title_de],
                ['title_ru' => $title_ru ?: null]
            );
        }

        fclose($handle);
    }
}

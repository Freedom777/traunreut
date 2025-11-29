<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ImportCitiesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:cities {file : Path to CSV file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import cities from CSV file';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $file = $this->argument('file');

        if (!file_exists($file)) {
            $this->error("File not found: $file");
            return 1;
        }

        $statesMapping = [
            'Baden-Württemberg' => 'BW',
            'Bayern' => 'BY',
            'Berlin' => 'BE',
            'Brandenburg' => 'BB',
            'Bremen' => 'HB',
            'Hamburg' => 'HH',
            'Hessen' => 'HE',
            'Mecklenburg-Vorpommern' => 'MV',
            'Niedersachsen' => 'NI',
            'Nordrhein-Westfalen' => 'NW',
            'Rheinland-Pfalz' => 'RP',
            'Saarland' => 'SL',
            'Sachsen' => 'SN',
            'Sachsen-Anhalt' => 'ST',
            'Schleswig-Holstein' => 'SH',
            'Schlewig-Holstein' => 'SH', // Typo in CSV file
            'Thüringen' => 'TH',
        ];

        $this->info('Seeding states...');
        foreach ($statesMapping as $name => $code) {
            \App\Models\State::firstOrCreate(
                ['code' => $code],
                ['name' => $name]
            );
        }

        $this->info('Importing cities...');
        
        $handle = fopen($file, 'r');
        if ($handle === false) {
            $this->error("Could not open file: $file");
            return 1;
        }

        // Count lines for progress bar (optional, but good for UX)
        $lineCount = 0;
        while (fgets($handle)) {
            $lineCount++;
        }
        rewind($handle);

        $bar = $this->output->createProgressBar($lineCount);
        $bar->start();

        $header = fgetcsv($handle, 0, ';'); // Skip header if it exists, or handle it
        // Assuming first line is header: Ort;Plz;Bundesland
        
        while (($data = fgetcsv($handle, 0, ';')) !== false) {
            // Data structure: [0] => Ort, [1] => Plz, [2] => Bundesland
            if (count($data) < 3) {
                continue;
            }

            $cityName = trim($data[0]);
            $zipCode = trim($data[1]);
            $stateName = trim($data[2]);

            $stateCode = $statesMapping[$stateName] ?? null;

            if (!$stateCode) {
                $this->newLine();
                $this->warn("State '$stateName' not found for city '$cityName'. Skipping.");
                $bar->advance();
                continue;
            }

            \App\Models\City::updateOrCreate(
                ['zip_code' => $zipCode, 'name' => $cityName],
                ['state_code' => $stateCode]
            );

            $bar->advance();
        }

        fclose($handle);
        $bar->finish();
        $this->newLine();
        $this->info('Import completed.');
    }
}

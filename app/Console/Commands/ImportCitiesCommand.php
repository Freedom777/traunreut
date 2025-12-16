<?php

namespace App\Console\Commands;

use App\Models\City;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportCitiesCommand extends Command
{
    /**
     * Default path to the German cities CSV file
     */
    private const DEFAULT_CSV_PATH = 'csv/german-cities.csv';

    /**
     * Batch size for bulk inserts
     */
    private const BATCH_SIZE = 1000;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:cities {file? : Path to CSV file (optional)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import cities from CSV file with state codes';

    /**
     * Execute the console command.
     * 
     * Note: For even better performance with very large datasets, you can use LOAD DATA INFILE:
     * 
     * DB::statement("
     *     LOAD DATA LOCAL INFILE '{$absolutePath}'
     *     INTO TABLE cities
     *     FIELDS TERMINATED BY ';'
     *     LINES TERMINATED BY '\n'
     *     IGNORE 1 ROWS
     *     (name, zip_code, state_code)
     * ");
     * 
     * Requirements:
     * - MySQL FILE privilege
     * - local_infile=1 in MySQL config
     * - PDO::MYSQL_ATTR_LOCAL_INFILE => true in database config
     */
    public function handle()
    {
        $file = $this->argument('file') ?? base_path(self::DEFAULT_CSV_PATH);

        if (!file_exists($file)) {
            $this->error("File not found: $file");
            return 1;
        }

        $this->info("Importing cities from: $file");
        
        $handle = fopen($file, 'r');
        if ($handle === false) {
            $this->error("Could not open file: $file");
            return 1;
        }

        // Count lines for progress bar
        $lineCount = 0;
        while (fgets($handle)) {
            $lineCount++;
        }
        rewind($handle);

        // Skip header
        $header = fgetcsv($handle, 0, ';');
        $lineCount--; // Adjust for header

        $bar = $this->output->createProgressBar($lineCount);
        $bar->start();

        $batch = [];
        $processedCount = 0;
        $skippedCount = 0;

        while (($data = fgetcsv($handle, 0, ';')) !== false) {
            // Data structure: [0] => Ort (City), [1] => Plz (Zip), [2] => Bundesland (State Code)
            if (count($data) < 3) {
                $skippedCount++;
                $bar->advance();
                continue;
            }

            $cityName = trim($data[0]);
            $zipCode = trim($data[1]);
            $stateCode = trim($data[2]);

            // Skip empty entries
            if (empty($cityName) || empty($zipCode) || empty($stateCode)) {
                $skippedCount++;
                $bar->advance();
                continue;
            }

            $batch[] = [
                'name' => $cityName,
                'zip_code' => $zipCode,
                'state_code' => $stateCode,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            // Insert batch when it reaches the batch size
            if (count($batch) >= self::BATCH_SIZE) {
                $this->insertBatch($batch);
                $processedCount += count($batch);
                $batch = [];
            }

            $bar->advance();
        }

        // Insert remaining records
        if (!empty($batch)) {
            $this->insertBatch($batch);
            $processedCount += count($batch);
        }

        fclose($handle);
        $bar->finish();
        $this->newLine();
        
        $this->info("Import completed!");
        $this->info("Processed: $processedCount cities");
        if ($skippedCount > 0) {
            $this->warn("Skipped: $skippedCount invalid entries");
        }

        return 0;
    }

    /**
     * Insert a batch of cities using upsert (insert or update on duplicate)
     *
     * @param array $batch
     * @return void
     */
    private function insertBatch(array $batch): void
    {
        // Use upsert to handle duplicates (update if exists, insert if not)
        City::upsert(
            $batch,
            ['zip_code', 'name'], // Unique keys
            ['state_code', 'updated_at'] // Columns to update on duplicate
        );
    }
}

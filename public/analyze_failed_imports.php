<?php

require __DIR__.'/vendor/autoload.php';
$app = require __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$file = 'csv/german-postcodes.csv';
if (!file_exists($file)) {
    die("CSV file not found: $file\n");
}
$handle = fopen($file, 'r');

// Standard mapping
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
    'Schlewig-Holstein' => 'SH', // Typo
    'Thüringen' => 'TH',
];

echo "Loading existing cities from DB...\n";
// Load all cities into memory for fast lookup
// Key: zip_code|name (lowercase)
$existingCities = DB::table('cities')
    ->select('zip_code', 'name', 'state_code')
    ->get()
    ->mapWithKeys(function ($item) {
        $key = $item->zip_code . '|' . mb_strtolower(trim($item->name));
        return [$key => $item->state_code];
    })
    ->toArray();

echo "Loaded " . count($existingCities) . " cities.\n";
echo "Analyzing CSV...\n\n";

fgetcsv($handle, 0, ';'); // Skip header
$row = 1;
$missing = [];
$encodingIssues = [];
$mismatchedState = [];
$seenKeys = [];

while (($data = fgetcsv($handle, 0, ';')) !== false) {
    $row++;
    
    if (count($data) < 3) {
        continue;
    }

    $city = trim($data[0]);
    $zip = trim($data[1]);
    $state = trim($data[2]);

    if (empty($city) || empty($zip)) {
        continue;
    }

    // Check for encoding issues in CSV data
    $replacementChar = "\xEF\xBF\xBD"; // UTF-8 replacement character
    $hasReplacement = strpos($city, $replacementChar) !== false || strpos($state, $replacementChar) !== false;
    $isValidUtf8 = mb_check_encoding($city, 'UTF-8') && mb_check_encoding($state, 'UTF-8');

    if (!$isValidUtf8 || $hasReplacement) {
        $encodingIssues[] = [
            'row' => $row,
            'city' => $city,
            'zip' => $zip,
            'state' => $state,
            'reason' => !$isValidUtf8 ? 'Invalid UTF-8' : 'Contains Replacement Character'
        ];
    }

    // Check for potential double encoding (common in German imports: Ã¼ instead of ü)
    // This is a heuristic check
    if (preg_match('/Ã[¼¶¤Ÿ]/', $city) || preg_match('/Ã[¼¶¤Ÿ]/', $state)) {
         $encodingIssues[] = [
            'row' => $row,
            'city' => $city,
            'zip' => $zip,
            'state' => $state,
            'reason' => 'Suspected Double Encoding'
        ];
    }

    $key = $zip . '|' . mb_strtolower($city);

    // Track duplicates
    if (isset($seenKeys[$key])) {
        $seenKeys[$key]++;
    } else {
        $seenKeys[$key] = 1;
    }

    if (!array_key_exists($key, $existingCities)) {
        $missing[] = [
            'row' => $row,
            'city' => $city,
            'zip' => $zip,
            'state' => $state
        ];
    } else {
        // Check if state matches
        $mappedState = $statesMapping[$state] ?? null;
        $dbState = $existingCities[$key];
        
        if ($mappedState && $dbState !== $mappedState) {
            $mismatchedState[] = [
                'row' => $row,
                'city' => $city,
                'zip' => $zip,
                'csv_state' => $state . " ($mappedState)",
                'db_state' => $dbState
            ];
        }
    }
}

fclose($handle);

// Calculate duplicates
$duplicatesCount = 0;
foreach ($seenKeys as $k => $count) {
    if ($count > 1) {
        $duplicatesCount += ($count - 1);
    }
}

echo "=== RESULTS ===\n";
echo "Total rows in CSV: " . ($row - 1) . "\n";
echo "Unique cities in DB: " . count($existingCities) . "\n";
echo "Duplicates in CSV: " . $duplicatesCount . "\n";
echo "Missing in DB: " . count($missing) . "\n";
echo "Encoding Issues: " . count($encodingIssues) . "\n";
echo "State Mismatches: " . count($mismatchedState) . "\n\n";

if ($duplicatesCount > 0) {
    echo "=== DUPLICATE EXAMPLES (First 10) ===\n";
    $shown = 0;
    foreach ($seenKeys as $k => $count) {
        if ($count > 1) {
            echo "$k: $count occurrences\n";
            $shown++;
            if ($shown >= 10) break;
        }
    }
    echo "\n";
}

if (count($encodingIssues) > 0) {
    echo "=== ENCODING ISSUES (First 50) ===\n";
    foreach (array_slice($encodingIssues, 0, 50) as $item) {
        echo "Row {$item['row']}: {$item['zip']} {$item['city']} ({$item['state']}) - {$item['reason']}\n";
    }
    echo "\n";
}

if (count($missing) > 0) {
    echo "=== MISSING RECORDS (First 50) ===\n";
    foreach (array_slice($missing, 0, 50) as $item) {
        echo "Row {$item['row']}: {$item['zip']} {$item['city']} ({$item['state']})\n";
    }
    echo "\n";
}

if (count($mismatchedState) > 0) {
    echo "=== STATE MISMATCHES (First 20) ===\n";
    foreach (array_slice($mismatchedState, 0, 20) as $item) {
        echo "Row {$item['row']}: {$item['zip']} {$item['city']} - CSV: {$item['csv_state']} != DB: {$item['db_state']}\n";
    }
}

<?php

$file = 'csv/german-postcodes.csv';
$handle = fopen($file, 'r');

if ($handle === false) {
    die("Cannot open file: $file\n");
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

$header = fgetcsv($handle, 0, ';'); // Skip header
$row = 1; // Header is row 1
$missingCount = 0;

echo "Analyzing missing imports...\n";
echo "Row | City | ZIP | State | Reason\n";
echo str_repeat('-', 60) . "\n";

while (($data = fgetcsv($handle, 0, ';')) !== false) {
    $row++;
    
    // Check for empty rows or invalid column count
    if (count($data) < 3) {
        echo sprintf("%4d | %-20s | %-5s | %-20s | %s\n", $row, $data[0] ?? '', $data[1] ?? '', '', 'Invalid format/Empty');
        $missingCount++;
        continue;
    }

    $city = trim($data[0]);
    $zip = trim($data[1]);
    $stateName = trim($data[2]);

    if (empty($city) && empty($zip) && empty($stateName)) {
        echo sprintf("%4d | %-20s | %-5s | %-20s | %s\n", $row, '', '', '', 'Empty row');
        $missingCount++;
        continue;
    }

    if (!isset($statesMapping[$stateName])) {
        // Try to detect encoding issues or partial matches
        $reason = "State not found: '$stateName'";
        
        // Check for encoding issues (e.g. UTF-8 read as ISO-8859-1)
        if (mb_detect_encoding($stateName, 'UTF-8', true) === false) {
             $reason .= " (Encoding issue?)";
        }
        
        echo sprintf("%4d | %-20s | %-5s | %-20s | %s\n", $row, substr($city, 0, 20), $zip, substr($stateName, 0, 20), $reason);
        $missingCount++;
    }
}

fclose($handle);

echo str_repeat('-', 60) . "\n";
echo "Total missing/skipped records: $missingCount\n";

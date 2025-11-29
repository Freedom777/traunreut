<?php

$file = 'csv/german-postcodes.csv';
$linesToInspect = [3583, 4386, 6945, 13559];

$handle = fopen($file, 'r');
$row = 0;

echo "Inspecting lines: " . implode(', ', $linesToInspect) . "\n\n";

while (($line = fgets($handle)) !== false) {
    $row++;
    if (in_array($row, $linesToInspect)) {
        echo "Line $row: " . trim($line) . "\n";
        echo "Hex: " . bin2hex($line) . "\n";
        
        // Parse it
        $data = str_getcsv($line, ';');
        echo "Parsed: " . print_r($data, true) . "\n";
        echo str_repeat('-', 40) . "\n";
    }
}

fclose($handle);

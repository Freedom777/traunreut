<?php

$file = 'csv/german-postcodes.csv';

echo "=== CSV ENCODING CHECK ===" . PHP_EOL;

// Check file encoding
$content = file_get_contents($file);
echo "File size: " . strlen($content) . " bytes" . PHP_EOL;

// Try to detect encoding
$encoding = mb_detect_encoding($content, ['UTF-8', 'ISO-8859-1', 'Windows-1252'], true);
echo "Detected encoding: " . ($encoding ?: 'Unknown') . PHP_EOL;

// Read with different encodings
echo "\n=== Sample with UTF-8 ===" . PHP_EOL;
$lines = file($file, FILE_IGNORE_NEW_LINES);
echo "Line 3584: " . $lines[3583] . PHP_EOL;
echo "Line 3585: " . $lines[3584] . PHP_EOL;

// Check for lines with only ZIP codes (no city or state)
echo "\n=== Lines with missing data ===" . PHP_EOL;
$emptyLines = 0;
$zipOnlyLines = 0;
$missingState = 0;

foreach ($lines as $i => $line) {
    if ($i === 0) continue; // Skip header
    
    $parts = explode(';', $line);
    
    if (count($parts) !== 3) {
        echo "Line " . ($i+1) . ": Invalid format - " . $line . PHP_EOL;
        continue;
    }
    
    $city = trim($parts[0]);
    $zip = trim($parts[1]);
    $state = trim($parts[2]);
    
    if (empty($city) && empty($zip) && empty($state)) {
        $emptyLines++;
    } elseif (!empty($zip) && empty($city) && empty($state)) {
        $zipOnlyLines++;
        if ($zipOnlyLines <= 5) {
            echo "ZIP only (line " . ($i+1) . "): " . $zip . PHP_EOL;
        }
    } elseif (!empty($city) && !empty($zip) && empty($state)) {
        $missingState++;
        if ($missingState <= 5) {
            echo "Missing state (line " . ($i+1) . "): " . $city . " (" . $zip . ")" . PHP_EOL;
        }
    }
}

echo "\n=== Summary ===" . PHP_EOL;
echo "Empty lines (;;): " . $emptyLines . PHP_EOL;
echo "ZIP-only lines: " . $zipOnlyLines . PHP_EOL;
echo "Missing state: " . $missingState . PHP_EOL;
echo "Total lines: " . count($lines) . PHP_EOL;

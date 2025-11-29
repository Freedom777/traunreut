<?php

$file = 'csv/german-postcodes.csv';
$handle = fopen($file, 'r');

echo "Dumping first 5 lines in Hex:\n\n";

for ($i = 0; $i < 5; $i++) {
    $line = fgets($handle);
    if ($line === false) break;
    
    echo "Line " . ($i + 1) . ": " . trim($line) . "\n";
    echo "Hex: " . bin2hex($line) . "\n";
    
    // Also parse with fgetcsv to see what it gets
    $lineStream = fopen('php://memory', 'r+');
    fwrite($lineStream, $line);
    rewind($lineStream);
    $data = fgetcsv($lineStream, 0, ';');
    
    echo "Parsed fields:\n";
    foreach ($data as $k => $v) {
        echo "  [$k] '$v' (Hex: " . bin2hex($v) . ")\n";
        echo "       Valid UTF-8: " . (mb_check_encoding($v, 'UTF-8') ? 'YES' : 'NO') . "\n";
    }
    echo str_repeat('-', 40) . "\n";
}

fclose($handle);

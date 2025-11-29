<?php

$inputFile = 'csv/german-postcodes.csv';
$outputFile = 'csv/german-postcodes-fixed.csv';

if (!file_exists($inputFile)) {
    die("Input file not found.\n");
}

$handleIn = fopen($inputFile, 'r');
$handleOut = fopen($outputFile, 'w');

$count = 0;
$fixedCount = 0;
$doubleEncodedCount = 0;
$win1252Count = 0;

echo "Fixing CSV encoding...\n";

while (($line = fgets($handleIn)) !== false) {
    $count++;
    $originalLine = $line;
    $isFixed = false;

    // 1. Check if valid UTF-8
    if (!mb_check_encoding($line, 'UTF-8')) {
        // Assume Windows-1252 and convert to UTF-8
        $line = mb_convert_encoding($line, 'UTF-8', 'Windows-1252');
        $win1252Count++;
        $isFixed = true;
    } else {
        // 2. If valid UTF-8, check for Double Encoding (e.g. Ã¼ instead of ü)
        // Common patterns: Ã¼ (ü), Ã¤ (ä), Ã¶ (ö), Ã (ß)
        // Ã¼ = C3 83 C2 BC
        // ü  = C3 BC
        if (preg_match('/Ã[¼¶¤Ÿ]/', $line) || strpos($line, 'Ã') !== false) {
             // Try to reverse double encoding
             // Convert FROM UTF-8 TO Windows-1252. 
             // If the result is valid UTF-8 and shorter, it was likely double encoded.
             $test = mb_convert_encoding($line, 'Windows-1252', 'UTF-8');
             
             // Check if the "reversed" string is valid UTF-8 and actually looks like German text
             // (e.g. contains umlauts but not the garbage sequences)
             if (mb_check_encoding($test, 'UTF-8')) {
                 // Verify it didn't just strip chars. 
                 // If we had "Ã¼", $test should have "ü".
                 // "Ã¼" len 4. "ü" len 2.
                 if (strlen($test) < strlen($line)) {
                     $line = $test;
                     $doubleEncodedCount++;
                     $isFixed = true;
                 }
             }
        }
    }

    fwrite($handleOut, $line);
    
    if ($isFixed && $fixedCount < 5) {
        echo "Fixed Line $count:\n";
        echo "Original: " . trim($originalLine) . "\n";
        echo "Fixed   : " . trim($line) . "\n\n";
    }
    if ($isFixed) $fixedCount++;
}

fclose($handleIn);
fclose($handleOut);

echo "Done.\n";
echo "Total lines processed: $count\n";
echo "Converted from Windows-1252: $win1252Count\n";
echo "Fixed Double Encoding: $doubleEncodedCount\n";
echo "Output saved to: $outputFile\n";
echo "\nPlease rename '$outputFile' to '$inputFile' if the results look correct.\n";

<?php
require __DIR__.'/vendor/autoload.php';
$app = require __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== EVENTS TABLE ===" . PHP_EOL;
$eventsColumns = DB::select('DESCRIBE events');
foreach ($eventsColumns as $col) {
    echo sprintf("%-20s %-15s %-10s %-10s\n", $col->Field, $col->Type, $col->Null, $col->Key);
}

echo "\n=== EVENTS_RU TABLE ===" . PHP_EOL;
$eventsRuColumns = DB::select('DESCRIBE events_ru');
foreach ($eventsRuColumns as $col) {
    echo sprintf("%-20s %-15s %-10s %-10s\n", $col->Field, $col->Type, $col->Null, $col->Key);
}

echo "\n=== REGION FIELD ANALYSIS ===" . PHP_EOL;
$regions = DB::table('events')->select('region')->distinct()->whereNotNull('region')->limit(20)->pluck('region');
echo "Distinct regions (first 20):" . PHP_EOL;
foreach ($regions as $region) {
    echo "  - " . $region . PHP_EOL;
}

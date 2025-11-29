<?php
require __DIR__.'/vendor/autoload.php';
$app = require __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== CITY FIELD ANALYSIS ===" . PHP_EOL;
echo "Current 'city' field type in events table:" . PHP_EOL;
$cityColumn = DB::select("SHOW COLUMNS FROM events LIKE 'city'");
if (!empty($cityColumn)) {
    foreach ($cityColumn as $col) {
        echo sprintf("%-20s %-15s %-10s\n", $col->Field, $col->Type, $col->Null);
    }
}

echo "\nSample city values from events (first 20):" . PHP_EOL;
$cities = DB::table('events')->select('city', 'region')->whereNotNull('city')->limit(20)->get();
foreach ($cities as $event) {
    echo sprintf("  City: %-20s Region: %s\n", $event->city, $event->region);
}

echo "\nDistinct cities count: " . DB::table('events')->distinct()->count('city') . PHP_EOL;

echo "\n=== CITIES TABLE ===" . PHP_EOL;
echo "Cities with state codes (first 10):" . PHP_EOL;
$citiesWithStates = DB::table('cities')->limit(10)->get();
foreach ($citiesWithStates as $city) {
    echo sprintf("  ID: %-5s Name: %-20s ZIP: %-10s State: %s\n", 
        $city->id, $city->name, $city->zip_code, $city->state_code);
}

echo "\n=== MATCHING ANALYSIS ===" . PHP_EOL;
echo "Events with city names that match cities table:" . PHP_EOL;
$matchCount = DB::table('events')
    ->join('cities', 'events.city', '=', 'cities.name')
    ->count();
echo "Matched: " . $matchCount . " events" . PHP_EOL;

$totalWithCity = DB::table('events')->whereNotNull('city')->count();
echo "Total events with city: " . $totalWithCity . PHP_EOL;
echo "Match rate: " . round(($matchCount / $totalWithCity) * 100, 2) . "%" . PHP_EOL;

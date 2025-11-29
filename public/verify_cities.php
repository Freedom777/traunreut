<?php
require __DIR__.'/vendor/autoload.php';
$app = require __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== IMPORT RESULTS ===" . PHP_EOL;
echo "Total cities: " . \App\Models\City::count() . PHP_EOL;
echo "Total states: " . \App\Models\State::count() . PHP_EOL;

echo "\nCities per state:" . PHP_EOL;
$states = \App\Models\State::withCount('cities')->get();
foreach ($states as $state) {
    echo sprintf("  %s (%s): %d cities\n", $state->name, $state->code, $state->cities_count);
}

echo "\nSample cities:" . PHP_EOL;
$cities = \App\Models\City::with('state')->limit(10)->get();
foreach ($cities as $city) {
    echo sprintf("  %s (%s) - %s\n", $city->name, $city->zip_code, $city->state->name ?? 'No state');
}

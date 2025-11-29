<?php

require __DIR__.'/vendor/autoload.php';
$app = require __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Event;
use App\Models\City;

echo "Checking Data Integrity...\n\n";

$totalEvents = Event::count();
$eventsWithCity = Event::whereNotNull('city_id')->count();
$eventsWithoutCity = Event::whereNull('city_id')->count();

echo "Total Events: $totalEvents\n";
echo "With City ID: $eventsWithCity\n";
echo "Without City ID: $eventsWithoutCity\n\n";

if ($eventsWithoutCity > 0) {
    echo "Events without City ID (First 10):\n";
    $events = Event::whereNull('city_id')->limit(10)->get();
    foreach ($events as $event) {
        echo "ID: {$event->id} | Location: '{$event->location}' | Title: '{$event->title}'\n";
    }
    echo "\n";
}

// Check for orphaned city_ids
$orphanedCount = Event::whereNotNull('city_id')
    ->whereDoesntHave('city') // Assuming relationship is defined in Event model
    ->count();

echo "Orphaned City IDs (exist in events but not in cities): $orphanedCount\n";

// Check if we can auto-assign cities to events without city_id
if ($eventsWithoutCity > 0) {
    echo "\nAttempting to match events without city_id to cities...\n";
    $matchable = 0;
    
    // We can reuse the logic from BaseParserController if we make it public or duplicate it here for testing
    // For now, let's just try simple exact match on location parts
    
    $events = Event::whereNull('city_id')->get();
    foreach ($events as $event) {
        if (empty($event->location)) continue;
        
        // Simple check: is the location a city name?
        $city = City::where('name', $event->location)->first();
        if ($city) {
            $matchable++;
            continue;
        }
        
        // Check if location contains city
        // This is expensive to do for all, so just a quick check for demo
    }
    
    echo "Potential simple matches found: $matchable\n";
}

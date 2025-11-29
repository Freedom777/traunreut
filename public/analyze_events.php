<?php
require __DIR__.'/vendor/autoload.php';
$app = require __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Total events: " . \App\Models\Event::count() . PHP_EOL;
echo "\nDistinct categories (first 30):" . PHP_EOL;
\App\Models\Event::select('category')->distinct()->limit(30)->get()->pluck('category')->each(function($c) {
    echo "  - " . ($c ?: '(null)') . PHP_EOL;
});

echo "\nEvent types (first 20):" . PHP_EOL;
\App\Models\Event::select('event_types')->whereNotNull('event_types')->limit(20)->get()->pluck('event_types')->each(function($t) {
    echo "  " . $t . PHP_EOL;
});

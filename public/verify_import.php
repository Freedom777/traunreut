<?php
require __DIR__.'/vendor/autoload.php';
$app = require __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "States: " . \App\Models\State::count() . PHP_EOL;
echo "Cities: " . \App\Models\City::count() . PHP_EOL;
$city = \App\Models\City::where('name', 'Traunreut')->first();
echo "Traunreut State: " . ($city ? $city->state_code : 'Not Found') . PHP_EOL;

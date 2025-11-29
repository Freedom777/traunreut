<?php

require __DIR__.'/vendor/autoload.php';
$app = require __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Http\Controllers\BaseParserController;
use Illuminate\Support\Facades\DB;

// Create a concrete class to test the abstract BaseParserController
class TestParserController extends BaseParserController {
    public function __construct() {}
    protected function fetchEvents(): array { return []; }
    protected function parseEventNode(\Symfony\Component\DomCrawler\Crawler $node): ?array { return null; }
    
    public function testParseCity($location) {
        $loc = $location;
        $zip = null;
        $city = $this->parseCity($loc, $zip);
        return ['original' => $location, 'city' => $city, 'zip' => $zip, 'remaining_location' => $loc];
    }
}

$controller = new TestParserController();

// Ensure we have some test cities
DB::table('cities')->updateOrInsert(['name' => 'Traunreut', 'zip_code' => '83301'], ['state_code' => 'BY']);
DB::table('cities')->updateOrInsert(['name' => 'München', 'zip_code' => '80331'], ['state_code' => 'BY']);

$testCases = [
    '83301 Traunreut, Rathausplatz',
    'Rathausplatz, 83301 Traunreut',
    'Marienplatz, München', // Standard format: Location, City
    'Some Place, UnknownCity', // Should return UnknownCity as fallback
    'Just A Place'
];

echo "Testing City Extraction...\n\n";

foreach ($testCases as $case) {
    $result = $controller->testParseCity($case);
    echo "Input: '{$case}'\n";
    echo "City : '{$result['city']}'\n";
    echo "ZIP  : '{$result['zip']}'\n";
    echo "Loc  : '{$result['remaining_location']}'\n";
    echo str_repeat('-', 40) . "\n";
}

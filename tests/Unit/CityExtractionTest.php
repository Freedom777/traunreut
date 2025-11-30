<?php

namespace Tests\Unit;

use App\Http\Controllers\BaseParserController;
use App\Models\City;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CityExtractionTest extends TestCase
{
    use RefreshDatabase;

    private CityExtractionTestController $controller;

    protected function setUp(): void
    {
        parent::setUp();
        config(['parse.test' => ['site' => 'test', 'url' => 'http://example.com']]);
        $this->controller = new CityExtractionTestController();
    }

    public function test_extract_city_with_zip()
    {
        $location = 'Hauptstr. 1, 83301 Traunreut';
        $city = $this->controller->testParseCity($location);

        $this->assertEquals('Traunreut', $city);
        $this->assertEquals('Hauptstr. 1', $location);
        
        $this->assertEquals('Traunreut', $city);
        $this->assertEquals('Hauptstr. 1', $location);
        
        // parseCity does not create the city in DB, it just parses the string.
        // So we should not assert database existence here.
    }

    public function test_extract_city_existing_in_db()
    {
        // Create existing city in DB
        City::create(['name' => 'Traunreut', 'zip_code' => '83301']);

        $location = '83301 Traunreut';
        $city = $this->controller->testParseCity($location);

        $this->assertEquals('Traunreut', $city);
        $this->assertEquals('', $location);
    }

    public function test_extract_city_fallback_known()
    {
        // Seed the city so it is "known"
        City::create(['name' => 'Traunreut']);

        $location = 'Some Place, Traunreut';
        $city = $this->controller->testParseCity($location);

        $this->assertEquals('Traunreut', $city);
        $this->assertEquals('Some Place', $location);
    }

    public function test_extract_city_fallback_unknown()
    {
        $location = 'Some Place, UnknownCity';
        $city = $this->controller->testParseCity($location);

        $this->assertEquals('UnknownCity', $city);
        // If city is unknown and no ZIP, parseCity returns it but does NOT strip it from location
        $this->assertEquals('Some Place, UnknownCity', $location);
        
        // Should not be saved to DB
        $this->assertDatabaseMissing('cities', ['name' => 'UnknownCity']);
    }
}

class CityExtractionTestController extends BaseParserController
{
    protected string $configPath = 'parse.test';
    // Removed createdCities and findOrCreateCity override to use real DB


    public function testParseCity(?string &$location = null): ?string
    {
        return $this->parseCity($location);
    }



    protected function fetchEvents(): array { return []; }
    protected function parseEventNode(\Symfony\Component\DomCrawler\Crawler $node): ?array { return null; }
}

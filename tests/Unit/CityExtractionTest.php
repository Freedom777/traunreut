<?php

namespace Tests\Unit;

use App\Http\Controllers\BaseParserController;
use App\Models\City;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CityExtractionTest extends TestCase
{
    // use RefreshDatabase; // Not needed as we mock DB

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
        
        $this->assertArrayHasKey('83301', $this->controller->createdCities);
        $this->assertEquals('Traunreut', $this->controller->createdCities['83301']);
    }

    public function test_extract_city_existing_in_db()
    {
        // Mock existing city by pre-filling the mock storage
        $this->controller->createdCities['83301'] = 'Traunreut';

        $location = '83301 Traunreut';
        $city = $this->controller->testParseCity($location);

        $this->assertEquals('Traunreut', $city);
        $this->assertEquals('', $location);
    }

    public function test_extract_city_fallback_known()
    {
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
        $this->assertEquals('Some Place', $location);
        
        // Should not be saved to DB (mock)
        $this->assertEmpty($this->controller->createdCities);
    }
}

class CityExtractionTestController extends BaseParserController
{
    protected string $configPath = 'parse.test';
    public array $createdCities = [];

    public function __construct()
    {
        parent::__construct();
    }

    public function testParseCity(?string &$location = null): ?string
    {
        return $this->parseCity($location);
    }

    protected function findOrCreateCity(string $zip, string $name): City
    {
        $this->createdCities[$zip] = $name;
        $city = new City();
        $city->name = $name;
        $city->zip_code = $zip;
        return $city;
    }

    protected function fetchEvents(): array { return []; }
    protected function parseEventNode(\Symfony\Component\DomCrawler\Crawler $node): ?array { return null; }
}

<?php

namespace Tests\Unit;

use App\Http\Controllers\BaseParserController;
use App\Models\EventType;
use App\Models\EventTypeKeyword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BaseParserTest extends TestCase
{
    use RefreshDatabase;

    private TestableBaseParserController $controller;

    protected function setUp(): void
    {
        parent::setUp();
        // We need to mock the config since the constructor uses it
        config(['parse.test' => ['site' => 'test', 'url' => 'http://example.com']]);
        $this->controller = new TestableBaseParserController();
    }

    public function test_parse_date_time_range()
    {
        // DD.MM.YYYY / HH:MM - HH:MM
        $input = '23.11.2025 / 18:00 - 20:00';
        $result = $this->controller->testParseDateTime($input);

        $this->assertEquals('2025-11-23 18:00:00', $result['start']);
        $this->assertEquals('2025-11-23 20:00:00', $result['end']);
    }

    public function test_parse_date_time_single()
    {
        // DD.MM.YYYY / HH:MM
        $input = '23.11.2025 / 18:00';
        $result = $this->controller->testParseDateTime($input);

        $this->assertEquals('2025-11-23 18:00:00', $result['start']);
        $this->assertNull($result['end']);
    }

    public function test_parse_date_only()
    {
        // DD.MM.YYYY
        $input = '23.11.2025';
        $result = $this->controller->testParseDateTime($input);

        $this->assertEquals('2025-11-23 00:00:00', $result['start']);
        $this->assertNull($result['end']);
    }

    public function test_clean_text()
    {
        $input = '  Hello &amp; World  ';
        $result = $this->controller->testCleanText($input);
        $this->assertEquals('Hello & World', $result);

        $this->assertNull($this->controller->testCleanText(''));
        $this->assertNull($this->controller->testCleanText(null));
    }

    public function test_parse_city()
    {
        $location = 'Hauptstr. 1, 83301 Traunreut';
        $city = $this->controller->testParseCity($location);
        
        $this->assertEquals('Traunreut', $city);
        $this->assertEquals('Hauptstr. 1', $location); // Location should be modified (popped)
    }

    public function test_parse_city_unknown()
    {
        $location = 'Some Place, UnknownCity';
        $city = $this->controller->testParseCity($location);
        
        $this->assertEquals('UnknownCity', $city);
    }

    public function test_determine_event_types()
    {
        // Seed data
        $sportType = EventType::create(['name' => 'Sport']);
        EventTypeKeyword::create(['keyword' => 'yoga', 'event_type_id' => $sportType->id]);

        $kulturType = EventType::create(['name' => 'Kultur']);
        EventTypeKeyword::create(['keyword' => 'museum', 'event_type_id' => $kulturType->id]);

        // Sport
        $types = $this->controller->testDetermineEventTypes('Sport', 'Yoga Class', 'Relaxing yoga');
        $this->assertContains($sportType->id, $types);

        // Kultur
        $types = $this->controller->testDetermineEventTypes('', 'Art Exhibition', 'Visit our museum');
        $this->assertContains($kulturType->id, $types);

        // Fallback to category
        $types = $this->controller->testDetermineEventTypes('Concert', 'Some Band', 'Music');
        $concertType = EventType::where('name', 'Concert')->first();
        $this->assertNotNull($concertType);
        $this->assertContains($concertType->id, $types);
    }
    public function test_run_flow()
    {
        $this->controller->run();
        
        $this->assertTrue($this->controller->fetchEventsCalled);
        $this->assertTrue($this->controller->saveEventsCalled);
    }
}

class TestableBaseParserController extends BaseParserController
{
    protected string $configPath = 'parse.test';
    public bool $fetchEventsCalled = false;
    public bool $saveEventsCalled = false;

    public function __construct()
    {
        // Bypass parent constructor logic that might exit, or mock it.
        // Parent constructor calls config() and HttpClient::create().
        // We can let it run if we mocked config properly in setUp.
        parent::__construct();
    }

    public function testParseDateTime(string $infoText): array
    {
        return $this->parseDateTime($infoText);
    }

    public function testCleanText(?string $text): ?string
    {
        return $this->cleanText($text);
    }

    public function testParseCity(?string &$location = null): ?string
    {
        return $this->parseCity($location);
    }

    public function testDetermineEventTypes(string $category, string $title, string $description): ?array
    {
        return $this->determineEventTypes($category, $title, $description);
    }

    protected function fetchEvents(): array
    {
        $this->fetchEventsCalled = true;
        return [['title' => 'Test Event']];
    }

    protected function saveEvents(array $events): void
    {
        $this->saveEventsCalled = true;
    }

    protected function getProcessedIdEvents(): void
    {
        // Mocked
    }

    protected function getProcessedHashEvents(): void
    {
        // Mocked
    }

    protected function parseEventNode(\Symfony\Component\DomCrawler\Crawler $node): ?array
    {
        return null;
    }
}

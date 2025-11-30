<?php

namespace Tests\Unit;

use App\Http\Controllers\BaseParserController;
use App\Models\EventType;
use App\Models\EventTypeKeyword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventTypeDeterminationTest extends TestCase
{
    use RefreshDatabase;

    private EventTypeTestController $controller;

    protected function setUp(): void
    {
        parent::setUp();
        config(['parse.test' => ['site' => 'test', 'url' => 'http://example.com']]);
        $this->controller = new EventTypeTestController();
    }

    public function test_determine_types_from_db()
    {
        // Seed data
        $sport = EventType::create(['name' => 'Sport']);
        EventTypeKeyword::create(['keyword' => 'yoga', 'event_type_id' => $sport->id]);

        $kultur = EventType::create(['name' => 'Kultur']);
        EventTypeKeyword::create(['keyword' => 'museum', 'event_type_id' => $kultur->id]);
        
        // We don't need to mock keywords anymore if we use the real DB and the controller uses it.
        // But the test controller overrides getEventKeywords to return mockKeywords.
        // We should probably remove that override or update it to return all from DB.
        // Let's update the test controller below to NOT override getEventKeywords, or use the parent's one.
        // Actually, let's just use the real controller logic.
        
        $types = $this->controller->testDetermineEventTypes('Fitness', 'Yoga Class', 'Relaxing');
        $this->assertContains($sport->id, $types);
        $this->assertNotContains($kultur->id, $types);

        $types = $this->controller->testDetermineEventTypes('', 'Art Museum', 'Exhibition');
        $this->assertContains($kultur->id, $types);
    }

    public function test_determine_types_fallback_category()
    {
        $concert = EventType::create(['name' => 'Concert']);
        
        $types = $this->controller->testDetermineEventTypes('Concert & Music', 'Some Band', 'Live');
        $this->assertContains($concert->id, $types);
    }
}

class EventTypeTestController extends BaseParserController
{
    protected string $configPath = 'parse.test';
    // Removed mockKeywords and getEventKeywords override to use real DB


    public function testDetermineEventTypes(string $category, string $title, string $description): ?array
    {
        return $this->determineEventTypes($category, $title, $description);
    }



    protected function fetchEvents(): array { return []; }
    protected function parseEventNode(\Symfony\Component\DomCrawler\Crawler $node): ?array { return null; }
}

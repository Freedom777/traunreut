<?php

namespace Tests\Unit;

use App\Http\Controllers\BaseParserController;
use App\Models\EventTypeKeyword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventTypeDeterminationTest extends TestCase
{
    // use RefreshDatabase;

    private EventTypeTestController $controller;

    protected function setUp(): void
    {
        parent::setUp();
        config(['parse.test' => ['site' => 'test', 'url' => 'http://example.com']]);
        $this->controller = new EventTypeTestController();
    }

    public function test_determine_types_from_db()
    {
        // Mock keywords
        $k1 = new EventTypeKeyword(); $k1->keyword = 'yoga'; $k1->type = 'Sport';
        $k2 = new EventTypeKeyword(); $k2->keyword = 'museum'; $k2->type = 'Kultur';
        
        $this->controller->mockKeywords = collect([$k1, $k2]);

        $types = $this->controller->testDetermineEventTypes('Fitness', 'Yoga Class', 'Relaxing');
        $this->assertContains('Sport', $types);
        $this->assertNotContains('Kultur', $types);

        $types = $this->controller->testDetermineEventTypes('', 'Art Museum', 'Exhibition');
        $this->assertContains('Kultur', $types);
    }

    public function test_determine_types_fallback_category()
    {
        $this->controller->mockKeywords = collect([]);
        $types = $this->controller->testDetermineEventTypes('Concert & Music', 'Some Band', 'Live');
        $this->assertContains('Concert', $types);
    }
}

class EventTypeTestController extends BaseParserController
{
    protected string $configPath = 'parse.test';
    public $mockKeywords;

    public function __construct()
    {
        parent::__construct();
        $this->mockKeywords = collect([]);
    }

    public function testDetermineEventTypes(string $category, string $title, string $description): ?array
    {
        return $this->determineEventTypes($category, $title, $description);
    }

    protected function getEventKeywords()
    {
        return $this->mockKeywords;
    }

    protected function fetchEvents(): array { return []; }
    protected function parseEventNode(\Symfony\Component\DomCrawler\Crawler $node): ?array { return null; }
}

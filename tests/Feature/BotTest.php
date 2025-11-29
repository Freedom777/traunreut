<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\EventTitle;
use Carbon\Carbon;
use DefStudio\Telegraph\Models\TelegraphBot;
use DefStudio\Telegraph\Models\TelegraphChat;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BotTest extends TestCase
{
    use RefreshDatabase;

    protected TelegraphBot $bot;
    protected TelegraphChat $chat;

    protected function setUp(): void
    {
        parent::setUp();

        $this->bot = TelegraphBot::create([
            'token' => 'test-token',
            'name' => 'Test Bot',
        ]);

        $this->chat = $this->bot->chats()->create([
            'chat_id' => '123456789',
            'name' => 'Test Chat',
        ]);
    }

    public function test_start_command_returns_main_menu(): void
    {
        // Mock the Telegraph API request to avoid actual network calls and to verify assertions
        // Telegraph uses Laravel's Http facade, so we can fake it.
        \Illuminate\Support\Facades\Http::fake([
            'api.telegram.org/*' => \Illuminate\Support\Facades\Http::response(['ok' => true, 'result' => []], 200),
        ]);

        $response = $this->postJson("/telegraph/{$this->bot->token}/webhook", [
            'update_id' => 1,
            'message' => [
                'message_id' => 1,
                'from' => [
                    'id' => 123456789,
                    'is_bot' => false,
                    'first_name' => 'Test',
                    'language_code' => 'ru',
                ],
                'chat' => [
                    'id' => 123456789,
                    'type' => 'private',
                ],
                'date' => time(),
                'text' => '/start',
                'entities' => [
                    [
                        'offset' => 0,
                        'length' => 6,
                        'type' => 'bot_command',
                    ]
                ]
            ],
        ]);

        $response->assertStatus(200);

        // Verify that a message was sent to Telegram with the expected text
        \Illuminate\Support\Facades\Http::assertSent(function ($request) {
            return $request->url() == "https://api.telegram.org/bot{$this->bot->token}/sendMessage" &&
                   str_contains($request['text'], 'Выберите способ поиска событий');
        });
    }

    public function test_today_returns_events(): void
    {
        \Illuminate\Support\Facades\Http::fake([
            'api.telegram.org/*' => \Illuminate\Support\Facades\Http::response(['ok' => true, 'result' => []], 200),
        ]);

        // Seed event
        $eventTitle = EventTitle::create([
            'title_ru' => 'Test Event Title',
            'title_de' => 'Test Event Title DE',
        ]);
        
        Event::create([
            'event_title_id' => $eventTitle->id,
            'start_date' => Carbon::now(),
            'end_date' => Carbon::now()->addHour(),
            'link' => 'http://example.com',
            'site' => 'example.com',
            'event_id' => 'test-1',
        ]);

        $response = $this->postJson("/telegraph/{$this->bot->token}/webhook", [
            'update_id' => 2,
            'message' => [
                'message_id' => 2,
                'from' => [
                    'id' => 123456789,
                    'is_bot' => false,
                    'first_name' => 'Test',
                    'language_code' => 'ru',
                ],
                'chat' => [
                    'id' => 123456789,
                    'type' => 'private',
                ],
                'date' => time(),
                'text' => 'Сегодня',
            ],
        ]);

        $response->assertStatus(200);

        \Illuminate\Support\Facades\Http::assertSent(function ($request) {
            return $request->url() == "https://api.telegram.org/bot{$this->bot->token}/sendMessage" &&
                   str_contains($request['text'], 'Test Event Title');
        });
    }

    public function test_city_search_returns_cities(): void
    {
        \Illuminate\Support\Facades\Http::fake([
            'api.telegram.org/*' => \Illuminate\Support\Facades\Http::response(['ok' => true, 'result' => []], 200),
        ]);

        // Seed cities
        $city1 = \App\Models\City::create(['name' => 'Berlin']);
        $city2 = \App\Models\City::create(['name' => 'Munich']);

        // Seed events linked to cities
        $eventTitle1 = EventTitle::create(['title_de' => 'Event in Berlin']);
        $eventTitle2 = EventTitle::create(['title_de' => 'Event in Munich']);
        
        Event::create([
            'event_title_id' => $eventTitle1->id,
            'city_id' => $city1->id,
            'start_date' => Carbon::now(),
            'end_date' => Carbon::now()->addHour(),
            'link' => 'http://example.com',
            'site' => 'example.com',
            'event_id' => 'test-2',
        ]);

        Event::create([
            'event_title_id' => $eventTitle2->id,
            'city_id' => $city2->id,
            'start_date' => Carbon::now(),
            'end_date' => Carbon::now()->addHour(),
            'link' => 'http://example.com',
            'site' => 'example.com',
            'event_id' => 'test-3',
        ]);

        $response = $this->postJson("/telegraph/{$this->bot->token}/webhook", [
            'update_id' => 3,
            'message' => [
                'message_id' => 3,
                'from' => [
                    'id' => 123456789,
                    'is_bot' => false,
                    'first_name' => 'Test',
                    'language_code' => 'ru',
                ],
                'chat' => [
                    'id' => 123456789,
                    'type' => 'private',
                ],
                'date' => time(),
                'text' => '🏙️ По городу',
            ],
        ]);

        $response->assertStatus(200);

        \Illuminate\Support\Facades\Http::assertSent(function ($request) {
            // Check for inline keyboard with cities
            if ($request->url() !== "https://api.telegram.org/bot{$this->bot->token}/sendMessage") {
                return false;
            }
            
            $data = json_decode($request->body(), true);
            $keyboard = $data['reply_markup'];
            
            // Flatten buttons to search for city names
            $buttons = [];
            foreach ($keyboard['inline_keyboard'] as $row) {
                foreach ($row as $btn) {
                    $buttons[] = $btn['text'];
                }
            }
            
            return in_array('Berlin', $buttons) && in_array('Munich', $buttons);
        });
    }

    public function test_unknown_command_returns_welcome(): void
    {
        \Illuminate\Support\Facades\Http::fake([
            'api.telegram.org/*' => \Illuminate\Support\Facades\Http::response(['ok' => true, 'result' => []], 200),
        ]);

        $response = $this->postJson("/telegraph/{$this->bot->token}/webhook", [
            'update_id' => 4,
            'message' => [
                'message_id' => 4,
                'from' => [
                    'id' => 123456789,
                    'is_bot' => false,
                    'first_name' => 'Test',
                    'language_code' => 'ru',
                ],
                'chat' => [
                    'id' => 123456789,
                    'type' => 'private',
                ],
                'date' => time(),
                'text' => 'Unknown Command',
            ],
        ]);

        $response->assertStatus(200);

        \Illuminate\Support\Facades\Http::assertSent(function ($request) {
            return $request->url() == "https://api.telegram.org/bot{$this->bot->token}/sendMessage" &&
                   str_contains($request['text'], 'Для начала работы нажмите кнопку Старт');
        });
    }

    public function test_period_selection_with_multiple_cities_asks_for_city(): void
    {
        \Illuminate\Support\Facades\Http::fake([
            'api.telegram.org/*' => \Illuminate\Support\Facades\Http::response(['ok' => true, 'result' => []], 200),
        ]);

        $tomorrow = Carbon::now()->addDay();

        // City A
        $city1 = \App\Models\City::create(['name' => 'City A']);
        $eventTitle1 = EventTitle::create(['title_de' => 'Event in City A']);
        Event::create([
            'event_title_id' => $eventTitle1->id,
            'city_id' => $city1->id,
            'start_date' => $tomorrow,
            'end_date' => $tomorrow->copy()->addHour(),
            'link' => 'http://example.com',
            'site' => 'example.com',
            'event_id' => 'test-4',
        ]);

        // City B
        $city2 = \App\Models\City::create(['name' => 'City B']);
        $eventTitle2 = EventTitle::create(['title_de' => 'Event in City B']);
        Event::create([
            'event_title_id' => $eventTitle2->id,
            'city_id' => $city2->id,
            'start_date' => $tomorrow,
            'end_date' => $tomorrow->copy()->addHour(),
            'link' => 'http://example.com',
            'site' => 'example.com',
            'event_id' => 'test-5',
        ]);

        $response = $this->postJson("/telegraph/{$this->bot->token}/webhook", [
            'update_id' => 5,
            'message' => [
                'message_id' => 5,
                'from' => [
                    'id' => 123456789,
                    'is_bot' => false,
                    'first_name' => 'Test',
                    'language_code' => 'ru',
                ],
                'chat' => [
                    'id' => 123456789,
                    'type' => 'private',
                ],
                'date' => time(),
                'text' => 'Завтра',
            ],
        ]);

        $response->assertStatus(200);

        \Illuminate\Support\Facades\Http::assertSent(function ($request) {
            if ($request->url() !== "https://api.telegram.org/bot{$this->bot->token}/sendMessage") {
                return false;
            }
            
            $text = $request['text'];
            if (!str_contains($text, 'Выберите город или посмотрите все события')) {
                return false;
            }

            $data = json_decode($request->body(), true);
            $keyboard = $data['reply_markup'];
            
            $buttons = [];
            foreach ($keyboard['inline_keyboard'] as $row) {
                foreach ($row as $btn) {
                    $buttons[] = $btn['text'];
                }
            }
            
            return in_array('City A', $buttons) && in_array('City B', $buttons) && in_array('🌍 Все города', $buttons);
        });
    }

    public function test_callback_city_selection_returns_events(): void
    {
        \Illuminate\Support\Facades\Http::fake([
            'api.telegram.org/*' => \Illuminate\Support\Facades\Http::response(['ok' => true, 'result' => []], 200),
        ]);

        $tomorrow = Carbon::now()->addDay();

        $city = \App\Models\City::create(['name' => 'City A']);
        
        $eventTitle = EventTitle::create([
            'title_ru' => 'Event in City A',
            'title_de' => 'Event in City A DE',
        ]);
        
        Event::create([
            'event_title_id' => $eventTitle->id,
            'city_id' => $city->id,
            'start_date' => $tomorrow,
            'end_date' => $tomorrow->copy()->addHour(),
            'link' => 'http://example.com',
            'site' => 'example.com',
            'event_id' => 'test-6',
        ]);

        // Simulate callback query: action:filterPeriod;period:tomorrow;city:City A
        // Telegraph uses a specific format for callback data, usually handled by the library.
        // But since we are mocking the webhook, we can construct the payload manually.
        // The handler uses $this->data->get('...').
        // Telegraph parses the callback_query.data.
        // If we use standard Telegraph buttons, the data is usually `action:method;param1:value1;...`
        
        $callbackData = 'action:filterPeriod;period:tomorrow;city:' . $city->id;

        $response = $this->postJson("/telegraph/{$this->bot->token}/webhook", [
            'update_id' => 6,
            'callback_query' => [
                'id' => 12345,
                'from' => [
                    'id' => 123456789,
                    'is_bot' => false,
                    'first_name' => 'Test',
                    'language_code' => 'ru',
                ],
                'message' => [
                    'message_id' => 5,
                    'chat' => [
                        'id' => 123456789,
                        'type' => 'private',
                    ],
                    'date' => time(),
                    'text' => 'Previous message',
                ],
                'chat_instance' => 'ci1',
                'data' => $callbackData,
            ],
        ]);

        $response->assertStatus(200);

        \Illuminate\Support\Facades\Http::assertSent(function ($request) {
            return $request->url() == "https://api.telegram.org/bot{$this->bot->token}/sendMessage" &&
                   str_contains($request['text'], 'Event in City A');
        });
    }

    public function test_callback_select_city_returns_events(): void
    {
        \Illuminate\Support\Facades\Http::fake([
            'api.telegram.org/*' => \Illuminate\Support\Facades\Http::response(['ok' => true, 'result' => []], 200),
        ]);

        $city = \App\Models\City::create(['name' => 'City B']);
        
        $eventTitle = EventTitle::create([
            'title_ru' => 'Event in City B',
            'title_de' => 'Event in City B DE',
        ]);
        
        Event::create([
            'event_title_id' => $eventTitle->id,
            'city_id' => $city->id,
            'start_date' => Carbon::now()->addDays(2),
            'end_date' => Carbon::now()->addDays(2)->addHour(),
            'link' => 'http://example.com',
            'site' => 'example.com',
            'event_id' => 'test-7',
        ]);

        $callbackData = 'action:selectCity;city:' . $city->id;

        $response = $this->postJson("/telegraph/{$this->bot->token}/webhook", [
            'update_id' => 7,
            'callback_query' => [
                'id' => 67890,
                'from' => [
                    'id' => 123456789,
                    'is_bot' => false,
                    'first_name' => 'Test',
                    'language_code' => 'ru',
                ],
                'message' => [
                    'message_id' => 6,
                    'chat' => [
                        'id' => 123456789,
                        'type' => 'private',
                    ],
                    'date' => time(),
                    'text' => 'Previous message',
                ],
                'chat_instance' => 'ci2',
                'data' => $callbackData,
            ],
        ]);

        $response->assertStatus(200);

        \Illuminate\Support\Facades\Http::assertSent(function ($request) {
            return $request->url() == "https://api.telegram.org/bot{$this->bot->token}/sendMessage" &&
                   str_contains($request['text'], 'Event in City B');
        });
    }
}

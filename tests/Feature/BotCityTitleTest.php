<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\EventTitle;
use Carbon\Carbon;
use DefStudio\Telegraph\Models\TelegraphBot;
use DefStudio\Telegraph\Models\TelegraphChat;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BotCityTitleTest extends TestCase
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

    public function test_city_filter_shows_city_name_in_title_not_id(): void
    {
        \Illuminate\Support\Facades\Http::fake([
            'api.telegram.org/*' => \Illuminate\Support\Facades\Http::response(['ok' => true, 'result' => []], 200),
        ]);

        $tomorrow = Carbon::now()->addDay();

        $city = \App\Models\City::create(['name' => 'Traunreut']);
        
        $eventTitle = EventTitle::create([
            'title_ru' => 'Test Event',
            'title_de' => 'Test Event DE',
        ]);
        
        Event::create([
            'event_title_id' => $eventTitle->id,
            'city_id' => $city->id,
            'start_date' => $tomorrow,
            'end_date' => $tomorrow->copy()->addHour(),
            'link' => 'http://example.com',
            'site' => 'example.com',
            'event_id' => 'test-fix-1',
        ]);

        // Callback data as if user clicked the city button
        $callbackData = 'action:filterPeriod;period:tomorrow;city:' . $city->id;

        $response = $this->postJson("/telegraph/{$this->bot->token}/webhook", [
            'update_id' => 100,
            'callback_query' => [
                'id' => 999,
                'from' => [
                    'id' => 123456789,
                    'is_bot' => false,
                    'first_name' => 'Test',
                    'language_code' => 'ru',
                ],
                'message' => [
                    'message_id' => 50,
                    'chat' => [
                        'id' => 123456789,
                        'type' => 'private',
                    ],
                    'date' => time(),
                    'text' => 'Prev',
                ],
                'chat_instance' => 'ci1',
                'data' => $callbackData,
            ],
        ]);

        $response->assertStatus(200);

        \Illuminate\Support\Facades\Http::assertSent(function ($request) use ($city) {
            if ($request->url() !== "https://api.telegram.org/bot{$this->bot->token}/sendMessage") {
                return false;
            }
            
            $text = $request['text'];
            
            // Check that title contains "Traunreut"
            $hasCorrectTitle = str_contains($text, 'События') && str_contains($text, '(Traunreut)');
            
            // Check that title DOES NOT contain the ID in parens
            $hasIdInTitle = str_contains($text, '(' . $city->id . ')');
            
            return $hasCorrectTitle && !$hasIdInTitle;
        });
    }
}

<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\EventTitle;
use App\Models\City;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SoftDeleteDuplicateCheckTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Тест проверяет, что soft-deleted события учитываются при проверке дубликатов по event_id
     */
    public function test_soft_deleted_events_are_considered_in_duplicate_check()
    {
        // Создаём заголовок события
        $eventTitle = EventTitle::create([
            'title_de' => 'Test Event'
        ]);

        // Создаём событие
        $event = Event::create([
            'event_title_id' => $eventTitle->id,
            'site' => 'traunreut.de',
            'event_id' => '12345',
            'start_date' => '2025-12-01 19:00:00',
            'link' => 'https://example.com/event/12345',
        ]);

        // Проверяем, что событие существует
        $this->assertDatabaseHas('events', [
            'event_id' => '12345',
            'site' => 'traunreut.de',
            'deleted_at' => null
        ]);

        // Удаляем событие (soft delete)
        $event->delete();

        // Проверяем, что событие помечено как удалённое
        $this->assertSoftDeleted('events', [
            'event_id' => '12345',
            'site' => 'traunreut.de',
        ]);

        // Проверяем, что withTrashed() находит удалённое событие
        $foundEvent = Event::withTrashed()
            ->where('event_id', '12345')
            ->where('site', 'traunreut.de')
            ->first();

        $this->assertNotNull($foundEvent, 'Удалённое событие должно быть найдено с withTrashed()');
        $this->assertTrue($foundEvent->trashed(), 'Событие должно быть помечено как удалённое');

        // Проверяем, что обычный запрос НЕ находит удалённое событие
        $notFoundEvent = Event::where('event_id', '12345')
            ->where('site', 'traunreut.de')
            ->first();

        $this->assertNull($notFoundEvent, 'Удалённое событие НЕ должно быть найдено без withTrashed()');
    }

    /**
     * Тест проверяет, что getProcessedIdEvents() учитывает soft-deleted события,
     * а getProcessedHashEvents() НЕ учитывает их
     */
    public function test_processed_events_soft_delete_logic()
    {
        $eventTitle = EventTitle::create([
            'title_de' => 'Test Event'
        ]);

        $city = \App\Models\City::create(['name' => 'Test City']);

        // Создаём несколько событий
        $event1 = Event::create([
            'event_title_id' => $eventTitle->id,
            'site' => 'traunreut.de',
            'event_id' => '100',
            'start_date' => '2025-12-01 19:00:00',
            'link' => 'https://example.com/event/100',
            'city_id' => $city->id,
        ]);

        $event2 = Event::create([
            'event_title_id' => $eventTitle->id,
            'site' => 'traunreut.de',
            'event_id' => '200',
            'start_date' => '2025-12-02 19:00:00',
            'link' => 'https://example.com/event/200',
            'city_id' => $city->id,
        ]);

        // Удаляем первое событие
        $event1->delete();

        // Проверяем getProcessedIdEvents - должен включать удалённые
        $processedIds = Event::withTrashed()
            ->where('site', 'traunreut.de')
            ->pluck('event_id')
            ->toArray();

        $this->assertContains('100', $processedIds, 'Удалённое событие должно быть в списке event_id');
        $this->assertContains('200', $processedIds, 'Активное событие должно быть в списке event_id');
        $this->assertCount(2, $processedIds, 'Должно быть найдено 2 события по event_id');

        // Проверяем getProcessedHashEvents - НЕ должен включать удалённые
        $processedHashes = Event::join('event_titles', 'events.event_title_id', '=', 'event_titles.id')
            ->select(['events.start_date', 'event_titles.title_de', 'events.city_id'])
            ->where('events.site', 'traunreut.de')
            ->get()
            ->toArray();

        $this->assertCount(1, $processedHashes, 'Должно быть найдено только 1 активное событие по хэшу');
        $this->assertEquals('2025-12-02T19:00:00.000000Z', $processedHashes[0]['start_date']);
    }
}

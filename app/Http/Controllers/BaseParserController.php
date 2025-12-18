<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\City;
use App\Models\EventTitle;
use App\Models\EventType;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpClient\HttpClient;

abstract class BaseParserController extends Controller
{
    protected const TITLE_SIMILARITY_THRESHOLD = 90;

    abstract protected function fetchEvents(): array;

    public function run()
    {
        $this->log('Начинаем парсинг: ' . $this->parseConfig['url']);

        try {
            if (!$this->isLocalMode()) {
                $this->getProcessedIdEvents();
                $this->getProcessedHashEvents();
            }

            $events = $this->fetchEvents();

            $this->log('Общее количество событий для обработки: ' . $this->eventCount);

            $this->saveEvents($events);

        } catch (\Exception $e) {
            $this->log('Критическая ошибка при парсинге ' . $this->parseConfig['url'] . ': ' . $e->getMessage(), 'ERROR');
        }

        $this->log('Завершен парсинг ' . $this->parseConfig['url'] . ': найдено ' . $this->eventCount . ', добавлено ' . $this->successCount .
            ', дубликатов ' . $this->duplicateCount . ', ошибок ' . $this->errorCount);
    }

    abstract protected function parseEventNode(Crawler $node): ?array;



    protected HttpBrowser $client;
    protected array $processedIdEvents = [];
    protected array $processedHashEvents = [];

    protected int $duplicateCount = 0;
    protected int $errorCount = 0;
    protected int $successCount = 0;
    protected int $eventCount = 0;

    protected string $configPath = '';
    protected array $parseConfig = [];

    protected bool $debugMode = false;

    protected bool $localMode = false;

    public function __construct()
    {
        if (!$this->configPath) {
            Log::error('Config path not set');
            exit();
        }

        $this->parseConfig = config($this->configPath);

        if (!$this->parseConfig) {
            Log::error('Config is corrupted.');
            exit();
        }

        $this->client = new HttpBrowser(HttpClient::create([
            'timeout' => 30,
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) ' .
                    'AppleWebKit/537.36 (KHTML, like Gecko) ' .
                    'Chrome/91.0.4472.124 Safari/537.36',
            ],
        ]));

        $this->log("=== Парсер событий запущен: " . now()->toDateTimeString() . " ===");
    }

    protected function getProcessedIdEvents() : void {
        if (!empty($this->parseConfig['site'])) {
            $this->processedIdEvents = Event::withTrashed()
                ->where('site', $this->parseConfig['site'])
                ->pluck('event_id')
                ->toArray();
        }
    }

    protected function getProcessedHashEvents() : void {
        $this->processedHashEvents = Event::join('event_titles', 'events.event_title_id', '=', 'event_titles.id')
            ->select(['events.start_date', 'event_titles.title_de', 'events.city_id'])
            ->where('events.site', $this->parseConfig['site'])
            ->get()
            ->map(function ($event) {
                return [
                    'start_date' => $event->start_date ?? '',
                    'title' => $event->title_de,
                    'city_id' => $event->city_id
                ];
            })
            ->toArray();
    }

    protected function log(string $message, string $level = 'info'): void
    {
        Log::{$level}($message);

        if (app()->runningInConsole()) {
            echo '['.now()->toDateTimeString().'] ['.strtoupper($level).'] '.$message.PHP_EOL;
        }
    }

    public function isDebugMode(): bool
    {
        return $this->debugMode;
    }

    public function setDebugMode(bool $debugMode): void
    {
        $this->debugMode = $debugMode;
    }

    public function isLocalMode(): bool
    {
        return $this->localMode;
    }

    public function setLocalMode(bool $localMode): void
    {
        $this->localMode = $localMode;
    }

    /**
     * Парсинг HTML и получение массива событий
     */
    public function parseEvents(Crawler $crawler): array
    {
        $events = $crawler
            ->filter($this->parseConfig['parse']['event_list_selector'])
            ->each(fn(Crawler $node) => $this->parseEventNode($node));

        return array_values(array_filter($events, fn(?array $event): bool => $event !== null));
    }

    /**
     * Массовое сохранение событий
     */
    protected function saveEvents(array $events): void
    {
        if (empty($events)) {
            return;
        }

        try {
            // Extract event_type_ids before inserting
            $eventTypesMap = [];
            foreach ($events as $index => $event) {
                if (isset($event['event_type_ids'])) {
                    $eventTypesMap[$index] = $event['event_type_ids'];
                    unset($events[$index]['event_type_ids']);
                }
            }

            // Insert events
            Event::insert($events);

            // Attach event types via many-to-many relationship
            if (!empty($eventTypesMap)) {
                // Get the inserted events by their event_id
                foreach ($events as $index => $eventData) {
                    if (isset($eventTypesMap[$index]) && !empty($eventTypesMap[$index])) {
                        $event = Event::withTrashed()
                            ->where('event_id', $eventData['event_id'])
                            ->where('site', $eventData['site'])
                            ->first();
                        if ($event) {
                            $event->eventTypes()->sync($eventTypesMap[$index]);
                        }
                    }
                }
            }

            $this->log('Сохранено ' . count($events) . ' событий.');
        } catch (\Exception $e) {
            $this->log('Ошибка при сохранении событий: ' . $e->getMessage(), 'ERROR');
            $this->errorCount += count($events);
        }
    }

    protected function normalizeUrl(?string $url, string $baseUrl): ?string
    {
        if (empty($url)) {
            return null;
        }

        if (preg_match('#^https?://#', $url)) {
            return $url;
        }

        $parsedBase = parse_url($baseUrl);
        $scheme = $parsedBase['scheme'] ?? 'https';
        $host   = $parsedBase['host'] ?? '';

        if (str_starts_with($url, '/')) {
            return "{$scheme}://{$host}{$url}";
        }

        $basePath = dirname($parsedBase['path'] ?? '/');
        return "{$scheme}://{$host}{$basePath}/{$url}";
    }

    /**
     * Очистка текста от HTML-сущностей и лишних символов
     */
    protected function cleanText(?string $text): ?string
    {
        if (empty($text)) {
            return null;
        }
        $text = Str::squish(html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8'));

        return $text !== '' ? $text : null;
    }

    protected function checkExistEventById(int $eventId): bool
    {
        if (in_array($eventId, $this->processedIdEvents)) {
            return true;
        }
        $this->processedIdEvents[] = $eventId;
        return false;
    }

    protected function checkExistByDateTitleCity(?string $startDate, string $title, int $cityId): bool
    {
        // Check for similar events with exact date/city match and fuzzy title match
        foreach ($this->processedHashEvents as $processedEvent) {
            // Exact match for date and city
            if ($processedEvent['start_date'] === ($startDate ?? '') && $processedEvent['city_id'] === $cityId) {
                // Fuzzy match for title
                $similarity = 0;
                similar_text(
                    mb_strtolower($processedEvent['title']),
                    mb_strtolower($title),
                    $similarity
                );

                if ($similarity >= self::TITLE_SIMILARITY_THRESHOLD) {
                    return true;
                }
            }
        }

        // Add to processed events
        $this->processedHashEvents[] = [
            'start_date' => $startDate ?? '',
            'title' => $title,
            'city_id' => $cityId
        ];

        return false;
    }

    protected function filterNodeText(Crawler $node, string $selector): ?string {
        return $node
            ->filter($selector)
            ->first()
            ->text(default: '');
    }

    /**
     * Получение ID заголовка события по названию (или создание нового)
     */
    protected function getEventTitleId(string $title): int
    {
        // 1. Exact Match
        $eventTitleId = EventTitle::where('title_de', $title)->value('id');
        if ($eventTitleId) {
            return $eventTitleId;
        }

        // 2. Case Insensitive Check (Strict)
        $similarTitle = EventTitle::whereRaw('LOWER(title_de) = ?', [mb_strtolower($title)])->first();
        if ($similarTitle) {
            // Log the merge mapping for debugging
            if ($this->isDebugMode()) {
               $this->log("Title merged by case: '$title' -> '{$similarTitle->title_de}'");
            }
            return $similarTitle->id;
        }

        // 3. Typo Check (Levenshtein) - Optional/Lightweight
        // Running on all titles is too slow. We could fetch titles with same first letter and length +/- 1.
        // For now, let's stick to Case Insensitive which covers 80% of accidental duplicates.
        // User asked for "similar" checks, so let's try a optimized fetch.

        // Fetch candidates with same first 3 letters matching (optimization)
        if (mb_strlen($title) > 5) {
            $prefix = mb_substr($title, 0, 3);
            $candidates = EventTitle::where('title_de', 'like', $prefix . '%')->get();

            foreach ($candidates as $cand) {
                // Must be very close in length
                if (abs(mb_strlen($cand->title_de) - mb_strlen($title)) > 2) continue;

                $lev = levenshtein($title, $cand->title_de);
                if ($lev > 0 && $lev <= 1) { // Very strict: only 1 char difference allowed for auto-merge on import
                    if ($this->isDebugMode()) {
                        $this->log("Title merged by typo: '$title' -> '{$cand->title_de}'");
                    }
                    return $cand->id;
                }
            }
        }

        $eventTitle = new EventTitle();
        $eventTitle->title_de = $title;
        $eventTitle->save();

        return $eventTitle->id;
    }

    /**
     * Парсинг даты и времени из строки информации
     * Возвращает массив с start_date и end_date
     */
    protected function parseDateTime(string $infoText): array
    {
        $result = [
            'start' => null,
            'end' => null
        ];

        // Паттерн для диапазона времени: DD.MM.YYYY / HH:MM - HH:MM
        if (preg_match('/(\d{2})\.(\d{2})\.(\d{4})\s*\/\s*(\d{2}):(\d{2})\s*-\s*(\d{2}):(\d{2})/', $infoText, $matches)) {
            $date = "{$matches[3]}-{$matches[2]}-{$matches[1]}";
            $result['start'] = "{$date} {$matches[4]}:{$matches[5]}:00";
            $result['end'] = "{$date} {$matches[6]}:{$matches[7]}:00";
            return $result;
        }

        // Паттерн для одного времени: DD.MM.YYYY / HH:MM
        if (preg_match('/(\d{2})\.(\d{2})\.(\d{4})\s*\/\s*(\d{2}):(\d{2})/', $infoText, $matches)) {
            $result['start'] = "{$matches[3]}-{$matches[2]}-{$matches[1]} {$matches[4]}:{$matches[5]}:00";
            // Если есть только время начала, делаем end_date +1 час от start_date
            // $startTime = new DateTime($result['start']);
            // $startTime->modify('+1 hour');
            // $result['end'] = $startTime->format('Y-m-d H:i:s');
            return $result;
        }

        // Паттерн для даты без времени: DD.MM.YYYY
        if (preg_match('/(\d{2})\.(\d{2})\.(\d{4})/', $infoText, $matches)) {
            $result['start'] = "{$matches[3]}-{$matches[2]}-{$matches[1]} 00:00:00";
            // $result['end'] = "{$matches[3]}-{$matches[2]}-{$matches[1]} 23:59:59";
            return $result;
        }

        // Если не удалось распарсить, возвращаем null
        return $result;
    }

    /**
     * Определение типов событий на основе категории и содержимого
     * @return array Array of EventType IDs
     */
    protected function determineEventTypes(string $category, string $title, string $description): array
    {
        $content = mb_strtolower($category . ' ' . $title . ' ' . $description);

        // Получаем ключевые слова из БД с типами событий
        static $keywords = null;
        if ($keywords === null) {
            $keywords = $this->getEventKeywords();
        }

        $typeIds = [];

        foreach ($keywords as $keywordModel) {
            if (str_contains($content, mb_strtolower($keywordModel->keyword))) {
                $typeIds[] = $keywordModel->event_type_id;
            }
        }

        if (empty($typeIds) && !empty($category)) {
            // Извлекаем первую категорию из списка через &
            $firstCategory = trim(explode('&', $category)[0]);
            // Пытаемся найти или создать тип события по имени категории
            if ($firstCategory) {
                $eventType = EventType::firstOrCreate(['name' => $firstCategory]);
                $typeIds[] = $eventType->id;
            }
        }

        return array_values(array_unique(array_filter($typeIds)));
    }

    /**
     * Извлечение городов из локации
     */
    protected function parseCity(?string &$location = null, ?string &$zip = null): ?string
    {
        if (empty($location)) {
            return null;
        }

        // Пытаемся найти ZIP код и город: 5 цифр, пробел, название города
        // Пример: "83301 Traunreut"
        if (preg_match('/(\d{5})\s+([^,]+)/', $location, $matches)) {
            $zip = $matches[1];
            $cityName = trim($matches[2]);

            // Удаляем ZIP и город из локации, оставляя только улицу/место
            $location = trim(str_replace($matches[0], '', $location));
            $location = trim($location, ', ');

            return $cityName;
        }

        // Если ZIP не найден, пробуем найти город в конце строки, сверяясь с БД
        $parts = array_map('trim', explode(',', $location));
        $possibleCity = end($parts);

        // Проверяем точное совпадение с городами в БД
        $cityExists = City::where('name', $possibleCity)->exists();

        if ($cityExists) {
            // Удаляем последнюю часть из локации
            array_pop($parts);
            $location = implode(', ', $parts);
            return $possibleCity;
        }

        // Если не нашли известный город и нет ZIP, считаем последнюю часть городом
        return $possibleCity ?: null;
    }

    /**
     * Получение ID города по названию и ZIP коду
     */
    protected function getCityId(?string $cityName, ?string $zip = null): ?int
    {
        if (empty($cityName)) {
            return null;
        }

        // Try to find by ZIP first (most accurate)
        if ($zip) {
            $city = City::where('zip_code', $zip)->first();
            if ($city) {
                return $city->id;
            }
        }

        // Try to find by name
        $city = City::where('name', $cityName)->first();
        if ($city) {
            return $city->id;
        }

        // Create new city (without state if not found)
        $city = City::create([
            'name' => $cityName,
            'zip_code' => $zip,
            'state_code' => null // Will be updated later via data migration
        ]);

        return $city->id;
    }

    protected function findOrCreateCity(string $zip, string $name): \App\Models\City
    {
        return \App\Models\City::firstOrCreate(
            ['zip_code' => $zip],
            ['name' => $name]
        );
    }

    protected function getEventKeywords()
    {
        return \App\Models\EventTypeKeyword::all();
    }
}

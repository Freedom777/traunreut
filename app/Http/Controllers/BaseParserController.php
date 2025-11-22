<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventRu;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpClient\HttpClient;

abstract class BaseParserController extends Controller
{
    const KNOWN_CITIES = [
        'Bergen', 'Chieming', 'Eisenärzt', 'Fridolfing', 'Grabenstätt', 'Grassau', 'Herreninsel', 'Inzell', 'Kirchanschöring',
        'Marquartstein', 'Nußdorf', 'Oberwössen', 'Obing', 'Otting', 'Petting', 'Pittenhart', 'Reit im Winkl', 'Rottau',
        'Ruhpolding', 'Schleching', 'Seebruck', 'Seeon', 'Siegsdorf', 'Sondermoning', 'St. Leonhard', 'St. Leonhard am Wonneberg',
        'Staudach-Egerndach', 'Stein a.d. Traun', 'Taching', 'Taching am See', 'Tengling', 'Tettenhausen', 'Tirol', 'Tittmoning',
        'Traunreut', 'Traunstein', 'Trostberg', 'Truchtlaching', 'Übersee', 'Unterwössen', 'Waging', 'Waging am See', 'Weibhausen',
        'Wonneberg'
    ];

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
            $this->processedIdEvents = Event::where('site', $this->parseConfig['site'])
                ->pluck('event_id')
                ->toArray();
        }
    }

    protected function getProcessedHashEvents() : void {
        $this->processedHashEvents = Event::join('events_ru', 'events.events_ru_id', '=', 'events_ru.id')
            ->select(['events.start_date', 'events_ru.title_de', 'events.city'])
            ->get()
            ->map(fn($item) => ($item->start_date ?? '') . '_' . $item->title_de . '_' . $item->city)
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
            ->each(fn(Crawler $node) => static::parseEventNode($node));

        return array_values(array_filter($events, fn(?array $event): bool => $event !== null));
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

    protected function checkExistByDateTitleCity(?string $startDate, string $title, string $city): bool
    {
        $hash = ($startDate ?? '') . '_' . $title . '_' . $city;

        if (in_array($hash, $this->processedHashEvents)) {
            return true;
        }

        $this->processedHashEvents[] = $hash;
        return false;
    }

    protected function isEventDuplicateOld(array $event): bool
    {
        $hash = md5(strtolower($event['title'] . $event['start_date'] . $event['location']));

        if (in_array($hash, $this->processedIdEvents, true)) {
            return true;
        }

        $existing = Event::where('title', $event['title'])
            ->where('start_date', $event['start_date'])
            ->first();

        if ($existing) {
            if ($existing->location === $event['location']) {
                return true;
            }

            if ($existing->location && $event['location']) {
                $similarity = 0;
                similar_text(strtolower($existing->location), strtolower($event['location']), $similarity);

                if ($similarity > 80) {
                    return true;
                }
            }
        }

        $this->processedIdEvents[] = $hash;
        return false;
    }

    protected function filterNodeText(Crawler $node, string $selector): ?string {
        return $node
            ->filter($selector)
            ->first()
            ->text(default: '');
    }

    protected function getEventRuIdByTitle(string $title): int
    {
        $events_ru_id = EventRu::where('title_de', $title)->value('id');
        if (!$events_ru_id) {
            $eventsRu = new EventRu();
            $eventsRu->title_de = $title;
            $eventsRu->save();
            $events_ru_id = $eventsRu->id;
        }
        return $events_ru_id;
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
     */
    protected function determineEventTypes(string $category, string $title, string $description): ?array
    {
        $content = mb_strtolower($category . ' ' . $title . ' ' . $description);

        $typeMatchers = [
            'Sport' => ['sport', 'yoga', 'fitness', 'radtour', 'segway', 'biathlon', 'bogenschiessen', 'nordic-walking', 'workout', 'pilates', 'turnier'],
            'Kultur' => ['ausstellung', 'museen', 'kunstausstellung', 'museum'],
            'Familie' => ['kinder', 'familien', 'pony', 'bilderbuch', 'märchen'],
            'Gastronomie' => ['kulinarisches', 'brauerei', 'schnitzel', 'fondue', 'brauereiführung'],
            'Wellness' => ['gesundheit', 'meditation', 'massage', 'feldenkrais', 'entspann'],
            'Workshop' => ['workshop', 'kurs', 'schmuckdesign', 'malkurs'],
            'Natur' => ['naturerlebnisse', 'wanderherbst', 'wanderung', 'naturführung'],
            'Literatur' => ['lesung', 'literatur', 'autoren']
        ];

        $types = [];
        foreach ($typeMatchers as $type => $keywords) {
            foreach ($keywords as $keyword) {
                if (str_contains($content, $keyword)) {
                    $types[] = $type;
                    break;
                }
            }
        }

        if (empty($types) && !empty($category)) {
            // Извлекаем первую категорию из списка через &
            $firstCategory = explode('&', $category)[0];
            return [trim($firstCategory)] ?: null;
        }

        // return !empty($types) ? implode(', ', array_unique($types)) : null;
        return array_values(array_unique(array_filter($types)));
    }

    /**
     * Извлечение городов из локации
     */
    protected function parseCity(?string &$location = null): ?string
    {
        if (empty($location)) {
            return null;
        }

        $parts = array_map('trim', explode(',', $location));
        $city = array_pop($parts);
        $location = implode(', ', $parts);

        // Проверяем точное совпадение
        foreach (self::KNOWN_CITIES as $knownCity) {
            if (stripos($city, $knownCity) !== false) {
                return $knownCity;
            }
        }

        return $city ?: null;
    }
}

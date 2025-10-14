<?php

namespace App\Http\Controllers;

use App\Models\Event;
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
    protected array $processedEvents = [];

    protected int $duplicateCount = 0;
    protected int $errorCount = 0;
    protected int $successCount = 0;
    protected int $eventCount = 0;

    protected string $configPath = '';
    protected array $parseConfig = [];

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

        if (!empty($this->parseConfig['site'])) {
            $this->processedEvents = Event::where('site', $this->parseConfig['site'])
                ->pluck('event_id')
                ->toArray();
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

    protected function log(string $message, string $level = 'info'): void
    {
        Log::{$level}($message);

        if (app()->runningInConsole()) {
            echo '['.now()->toDateTimeString().'] ['.strtoupper($level).'] '.$message.PHP_EOL;
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

    protected function checkExistEvent(int $eventId): bool
    {
        if (in_array($eventId, $this->processedEvents)) {
            return true;
        }
        $this->processedEvents[] = $eventId;
        return false;
    }

    protected function isEventDuplicate(array $event): bool
    {
        $hash = $event['site'] . '_' . $event['event_id'];

        if (in_array($hash, $this->processedEvents)) {
            return true;
        }

        $this->processedEvents[] = $hash;
        return Event::where('site', $event['site'])
            ->where('event_id', $event['event_id'])
            ->exists();
    }

    protected function isEventDuplicateOld(array $event): bool
    {
        $hash = md5(strtolower($event['title'] . $event['start_date'] . $event['location']));

        if (in_array($hash, $this->processedEvents, true)) {
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

        $this->processedEvents[] = $hash;
        return false;
    }

    protected function filterNodeText(Crawler $node, string $selector): ?string {
        return $node
            ->filter($selector)
            ->first()
            ->text(default: '');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\HttpClient\HttpClient;
use Throwable;

class TraunreutController extends BaseParserController
{
    private string $baseUrl = 'https://veranstaltungen.traunreut.de';
    private string $source = 'traunreut.de';
    private string $region = 'Bayern';

    protected string $configPath = 'parse.traunreut';

    public function run()
    {
        $this->log('Начинаем парсинг: ' . $this->parseConfig['url']);

        try {
            $this->region = $this->parseConfig['region'];
            // Query to main site to get token and event_ids :START
            $crawler = $this->client->request('GET', $this->parseConfig['url']);
            // $response = $this->client->getResponse();
            // file_put_contents('start.html', $response->getContent());

            $scriptText = $crawler->filter('script')->eq(2)->text();
            // token: 'fFSSsbkAaQ4.'
            $checkToken = preg_match('#token: \'(.+?)\'#', $scriptText, $matches);
            if ($checkToken) {
                $token = $matches[1];
            } else {
                throw new \Exception('Can\'t find token');
            }

            $eventIdsContainer = $crawler->filter('#-IMXEVENT-results');
            $eventsListContainer = $eventIdsContainer->filter('div.-IMXEVNT-h-grid.-IMXEVNT-h-grid--fixed.-IMXEVNT-h-grid--margins');

            $linksAr = [];
            /*$eventsListContainer->each(function (Crawler $eventContainer) use (&$linksAr, $token, &$eventCount) {
                $eventAttr = $eventContainer->attr('data-idents');
                $eventIdsAr = explode(',', $eventAttr);
                $eventIdsAr = array_values(array_unique(array_filter($eventIdsAr)));
                $eventStr = '&object%5B%5D=' . implode('&object%5B%5D=', $eventIdsAr);
                $linksAr[] = 'https://veranstaltungen.traunreut.de/traunreut/de/action/items?widgetToken=' . $token . '&outputType=itemsPage' . $eventStr . '&layout=listitem&showDate=1';
                $this->eventCount += count($eventIdsAr);
            });*/
            $eventsListContainer->each(function (Crawler $eventContainer) use (&$linksAr, $token, &$eventCount) {
                $eventAttr = $eventContainer->attr('data-idents');
                $eventIdsAr = explode(',', $eventAttr);
                $eventIdsAr = array_values(array_unique(array_filter($eventIdsAr)));
                $countCurIds = count($eventIdsAr);
                $this->eventCount += $countCurIds;
                if ($countCurIds) {
                    $prefix = substr($eventIdsAr [0],0, 10);
                    $eventIdIntAr = array_map(fn($e) => (int)substr($e, 10), $eventIdsAr);
                    $eventIdIntAr = array_diff($eventIdIntAr, $this->processedEvents);
                    if ($eventIdIntAr) {
                        $eventIdsAr = array_map(fn($id) => $prefix . $id, $eventIdIntAr);
                    }
                }
                $this->duplicateCount += $countCurIds - count($eventIdsAr);

                if (!empty($eventIdsAr)) {
                    $eventStr = '&object%5B%5D=' . implode('&object%5B%5D=', $eventIdsAr);
                    $linksAr[] = 'https://veranstaltungen.traunreut.de/traunreut/de/action/items?widgetToken=' . $token . '&outputType=itemsPage' . $eventStr . '&layout=listitem&showDate=1';
                }
            });


            $this->log('Общее количество событий для обработки: ' . $this->eventCount);
            // Query to main site to get token and event_ids :END


            // https://veranstaltungen.traunreut.de/traunreut/?widgetToken=fFSSsbkAaQ4.&
            $client = new HttpBrowser(HttpClient::create());
            // Установить Referer
            $client->setServerParameter('HTTP_REFERER', 'https://veranstaltungen.traunreut.de/traunreut/?widgetToken=' . $token . '&');
            $events = [];
            foreach ($linksAr as $link) {
                $itemCrawler = $client->request('GET', $link);
                $events = array_merge($events, $this->parseEvents($itemCrawler));
            }
            if (!empty($events)) {
                Event::insert($events);
            }

        } catch (\Exception $e) {
            $this->log('Критическая ошибка при парсинге ' . $this->parseConfig['url'] . ': ' . $e->getMessage(), 'ERROR');
        }

        $this->log('Завершен парсинг ' . $this->parseConfig['url'] . ': найдено ' . $this->eventCount . ', добавлено ' . $this->successCount .
            ', дубликатов ' . $this->duplicateCount . ', ошибок ' . $this->errorCount);
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
     * Парсинг отдельного узла с событием
     */
    private function parseEventNode(Crawler $node): ?array
    {
        try {
            $eventId = $this->extractEventId($node);
            if ($this->checkExistEvent($eventId)) {
                ++$this->duplicateCount;
                return null;
            }

            $sourceParse = $this->parseConfig['parse'];
            $title = $this->filterNodeText($node, $sourceParse['title_selector']);
            if (empty($title)) {
                $this->log('Ошибка: Отсутствует наименование события с id = ' . $eventId, 'ERROR');
                ++$this->errorCount;
                return null;
            }

            $category = $this->filterNodeText($node, $sourceParse['category_selector']);
            $infoText = $this->filterNodeText($node, $sourceParse['info_selector']);
            $description = $this->filterNodeText($node, $sourceParse['description_selector']);

            $dates = $this->parseDateTime($infoText);
            $city = $this->parseCity($this->parseLocation($infoText));
            $eventTypes = $this->determineEventTypes($category, $title, $description);
            $currentDate = date('Y-m-d H:i:s');

            $eventRec = [
                'site' => $this->parseConfig['site'],
                'event_id' => $eventId,
                'category' => $category ?: null,
                'artist' => null, // В данном HTML нет информации об артистах
                'title' => $this->cleanText($title),
                'img' => $this->extractImage($node),
                'start_date' => $dates['start'],
                'end_date' => $dates['end'],
                'location' => $this->parseLocation($infoText),
                'link' => $this->extractLink($node),
                'region' => $this->region,
                'city' => $city,
                'event_types' => $eventTypes ? json_encode($eventTypes, JSON_UNESCAPED_UNICODE) : null,
                'source' => $this->source,
                'description' => $this->cleanText($description),
                'price' => null, // В данном HTML нет информации о ценах
                'is_active' => 1,
                'debug_html' => $node->html(),
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ];

            ++$this->successCount;
            return $eventRec;
        } catch (Throwable $e) {
            ++$this->errorCount;
            $this->log('Ошибка парсинга события: ' . $e->getMessage(), 'ERROR');
            return null;
        }
    }

    private function extractEventId(Crawler $node): ?int
    {
        return (int) substr($node->attr('data-ident'), 10);
    }

    private function extractImage(Crawler $node): ?string
    {
        $imgSrc = $node
            ->filter('img')
            ->first()
            ->attr('src');

        return $imgSrc ? $this->baseUrl . $imgSrc : null;
    }

    private function extractLink(Crawler $node): ?string
    {
        return $node
            ->filter('a[href*="traunreut.de"]')
            ->first()
            ->attr('href') ?: null;
    }

    /**
     * Парсинг даты и времени из строки информации
     * Возвращает массив с start_date и end_date
     */
    private function parseDateTime(string $infoText): array
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
     * Извлечение локации из строки информации
     */
    private function parseLocation(string $infoText): ?string
    {
        $parts = explode('/', $infoText);
        if (count($parts) >= 2) {
            return trim(end($parts)) ?: null;
        }
        return null;
    }

    /**
     * Извлечение городов из локации
     */
    private function parseCity(?string $location = null): ?string
    {
        if (empty($location)) {
            return null;
        }

        $parts = explode(',', $location);
        $city = trim(end($parts));

        // Проверяем точное совпадение
        foreach (self::KNOWN_CITIES as $knownCity) {
            if (stripos($city, $knownCity) !== false) {
                return $knownCity;
            }
        }

        return $city ?: null;
    }

    /**
     * Определение типов событий на основе категории и содержимого
     */
    private function determineEventTypes(string $category, string $title, string $description): ?array
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
}

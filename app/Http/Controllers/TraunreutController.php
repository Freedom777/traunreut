<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventTitle;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\HttpClient\HttpClient;
use Throwable;

class TraunreutController extends BaseParserController
{
    private string $baseUrl = 'https://veranstaltungen.traunreut.de';

    protected string $configPath = 'parse.traunreut';

    public function fetchEvents(): array
    {
        $token = $this->fetchToken();
        $linksAr = $this->fetchEventLinks($token);

        return $this->fetchEventDetails($linksAr, $token);
    }

    private function fetchToken(): string
    {
        $crawler = $this->client->request('GET', $this->parseConfig['url']);

        // Попытка найти скрипт с токеном более надежным способом
        $scriptText = '';
        $crawler->filter('script')->each(function (Crawler $node) use (&$scriptText) {
            if (str_contains($node->text(), "token: '")) {
                $scriptText = $node->text();
            }
        });

        if (empty($scriptText)) {
             // Fallback to previous method if specific search fails, but with check
             if ($crawler->filter('script')->count() > 2) {
                 $scriptText = $crawler->filter('script')->eq(2)->text();
             }
        }

        if (preg_match('#token: \'(.+?)\'#', $scriptText, $matches)) {
            return $matches[1];
        }

        throw new \Exception('Can\'t find token');
    }

    private function fetchEventLinks(string $token): array
    {
        $crawler = $this->client->getCrawler(); // Используем уже загруженный crawler из fetchToken (если клиент сохраняет состояние)
        // Но лучше явно передать или запросить снова, если состояние не гарантировано.
        // В данном случае, клиент HttpBrowser сохраняет последнее состояние, но fetchToken мог быть вызван отдельно.
        // Для надежности, так как мы уже на странице, используем текущий crawler клиента.

        $eventIdsContainer = $crawler->filter('#-IMXEVENT-results');
        if ($eventIdsContainer->count() === 0) {
             return [];
        }

        $eventsListContainer = $eventIdsContainer->filter('div.-IMXEVNT-h-grid.-IMXEVNT-h-grid--fixed.-IMXEVNT-h-grid--margins');

        $linksAr = [];
        $eventsListContainer->each(function (Crawler $eventContainer) use (&$linksAr, $token) {
            $eventAttr = $eventContainer->attr('data-idents');
            if (!$eventAttr) return;

            $eventIdsAr = explode(',', $eventAttr);
            $eventIdsAr = array_values(array_unique(array_filter($eventIdsAr)));
            $countCurIds = count($eventIdsAr);
            $this->eventCount += $countCurIds;

            if ($countCurIds) {
                $prefix = substr($eventIdsAr[0], 0, 10);
                $eventIdIntAr = array_map(fn($e) => (int)substr($e, 10), $eventIdsAr);
                $eventIdIntAr = array_diff($eventIdIntAr, $this->processedIdEvents);

                if ($eventIdIntAr) {
                    $eventIdsAr = array_map(fn($id) => $prefix . $id, $eventIdIntAr);
                } else {
                    $eventIdsAr = []; // Все отфильтрованы как дубликаты
                }
            }

            $this->duplicateCount += $countCurIds - count($eventIdsAr);

            if (!empty($eventIdsAr)) {
                $eventStr = '&object%5B%5D=' . implode('&object%5B%5D=', $eventIdsAr);
                $linksAr[] = $this->baseUrl . '/traunreut/de/action/items?widgetToken=' . $token . '&outputType=itemsPage' . $eventStr . '&layout=listitem&showDate=1';
            }
        });

        return $linksAr;
    }

    private function fetchEventDetails(array $linksAr, string $token): array
    {
        $client = new HttpBrowser(HttpClient::create());
        $client->setServerParameter('HTTP_REFERER', $this->baseUrl . '/traunreut/?widgetToken=' . $token . '&');

        $events = [];
        foreach ($linksAr as $link) {
            $itemCrawler = $client->request('GET', $link);
            $events = array_merge($events, $this->parseEvents($itemCrawler));
        }

        return $events;
    }

    /**
     * Парсинг HTML и получение массива событий
     */
    /**
     * Парсинг HTML и получение массива событий
     */
    public function parseEvents(Crawler $crawler): array
    {
        $events = parent::parseEvents($crawler);

        $seen = []; // uniqueKey => index in $events

        foreach ($events as $index => &$event) {
            $title = $event['title'] ?? '';
            unset($event['title']);

            $uniqueKey = $event['start_date'] . '_' . $title . '_' . $event['city'];
            unset($event['city']);

            if (isset($seen[$uniqueKey])) {
                $prevIndex = $seen[$uniqueKey];
                $events[$prevIndex]['deleted_at'] = now();
                ++$this->duplicateCount;
                // Since parseEventNode incremented successCount, we technically have a "successful parse"
                // but it's a duplicate. The original code didn't count it as success.
                // We should decrement successCount to match original logic if we want strict parity,
                // or accept that it's a "success" that is also a "duplicate".
                // Let's decrement to keep stats accurate to "saved" events (minus soft deleted).
                // Actually original code: if duplicate -> duplicateCount++, else successCount++.
                // Here we have successCount++ already. So we decrement it.
                --$this->successCount;
            }

            $seen[$uniqueKey] = $index;
        }
        unset($event);

        return array_values($events);
    }

    /**
     * Парсинг отдельного узла с событием
     */
    /**
     * Парсинг отдельного узла с событием
     */
    protected function parseEventNode(Crawler $node): ?array
    {
        try {
            $eventId = $this->extractEventId($node);
            if ($this->checkExistEventById($eventId)) {
                ++$this->duplicateCount;
                return null;
            }

            $sourceParse = $this->parseConfig['parse'];
            $title = $this->cleanText($this->filterNodeText($node, $sourceParse['title_selector']));
            if (empty($title)) {
                $this->log('Ошибка: Отсутствует наименование события с id = ' . $eventId, 'ERROR');
                ++$this->errorCount;
                return null;
            }
            $infoText = $this->filterNodeText($node, $sourceParse['info_selector']);
            $dates = $this->parseDateTime($infoText);

            $location = $this->parseLocation($infoText);
            // Removing city from location
            $zip = null;
            $cityName = $this->parseCity($location, $zip);
            $cityId = $this->getCityId($cityName, $zip);

            if ($this->checkExistByDateTitleCity($dates['start'], $title, $cityId)) {
                $eventTitleId = EventTitle::where('title_de', $title)->value('id');
                if ($eventTitleId) {
                    $count = Event::where(['start_date' => $dates['start'], 'event_title_id' => $eventTitleId, 'city_id' => $cityId])->delete();
                    $this->duplicateCount += $count;
                }
            }

            $eventTitleId = $this->getEventTitleId($title);
            $category = $this->filterNodeText($node, $sourceParse['category_selector']);
            $description = $this->filterNodeText($node, $sourceParse['description_selector']);
            $eventTypeIds = $this->determineEventTypes($category, $title, $description);
            $currentDate = date('Y-m-d H:i:s');

            $eventRec = [
                // 'title' => $title,
                'event_title_id' => $eventTitleId,
                'site' => $this->parseConfig['site'],
                'event_id' => $eventId,
                'category' => $category ?: null,
                'artist' => null, // В данном HTML нет информации об артистах
                'img' => $this->extractImage($node),
                'start_date' => $dates['start'],
                'end_date' => $dates['end'],
                'location' => $location,
                'link' => $this->extractLink($node),
                'city' => $cityName,
                'city_id' => $cityId,
                'event_type_ids' => $eventTypeIds, // Store IDs separately for later attachment
                'source' => $this->parseConfig['site'],
                'description' => $this->cleanText($description),
                'price' => null, // В данном HTML нет информации о ценах
                'is_active' => 1,
                'debug_html' => $node->html(),
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
                'deleted_at' => null,
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
            ->filter('a[href^="' . $this->baseUrl . '"]')
            ->first()
            ->attr('href') ?: null;
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
}

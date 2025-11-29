<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\DomCrawler\Crawler;
use Throwable;

class TraunsteinController extends BaseParserController
{
    private string $baseUrl = 'https://irs18whl.infomax.online';
    private string $source = 'traunreut.de';

    protected string $token = '';


    protected string $configPath = 'parse.traunstein';

    public function run()
    {
        $this->log('Начинаем парсинг: ' . $this->parseConfig['url']);

        try {
            if (!$this->isLocalMode()) {
                $this->getProcessedIdEvents();
                $this->getProcessedHashEvents();
            }

            $eventsListContainer = $this->parseMainForm();

            $linksAr = [];
            $eventsListContainer->each(function (Crawler $eventContainer) use (&$linksAr, &$eventCount) {
                $eventAttr = $eventContainer->attr('data-idents');
                $eventIdsAr = explode(',', $eventAttr);
                $eventIdsAr = array_values(array_unique(array_filter($eventIdsAr)));
                $countCurIds = count($eventIdsAr);
                $this->eventCount += $countCurIds;
                if ($countCurIds) {
                    $prefix = substr($eventIdsAr [0],0, 10);
                    $eventIdIntAr = array_map(fn($e) => (int)substr($e, 10), $eventIdsAr);
                    $eventIdIntAr = array_diff($eventIdIntAr, $this->processedIdEvents);
                    if ($eventIdIntAr) {
                        $eventIdsAr = array_map(fn($id) => $prefix . $id, $eventIdIntAr);
                    }
                }
                $this->duplicateCount += $countCurIds - count($eventIdsAr);

                // // https://irs18whl.infomax.online/traunstein/de/action/items?widgetToken=VV5JP7zDQHk.&outputType=itemsPage&object%5B%5D=eventdate_1952576&object%5B%5D=eventdate_1950954&object%5B%5D=eventdate_1952498&object%5B%5D=eventdate_1938968&object%5B%5D=eventdate_1981487&object%5B%5D=eventdate_1948826&object%5B%5D=eventdate_1849547&object%5B%5D=eventdate_1952949&object%5B%5D=eventdate_1826219&object%5B%5D=eventdate_1950887&layout=listitem&showDate=1
                if (!empty($eventIdsAr)) {
                    // layout=teasergroup
                    // widgetToken=VV5JP7zDQHk.
                    $eventStr = '&object%5B%5D=' . implode('&object%5B%5D=', $eventIdsAr);
                    $linksAr[] = $this->baseUrl . '/traunstein/de/action/items?widgetToken=' . $this->token . '&outputType=itemsPage' . $eventStr . '&layout=listitem&showDate=1';
                }
            });
            $this->log('Общее количество событий для обработки: ' . $this->eventCount);

            // Установить Referer
            $this->client->setServerParameter('HTTP_REFERER', $this->parseConfig['url']); // $this->baseUrl . '/traunreut/?widgetToken=' . $this->token . '&'
            $events = [];
            foreach ($linksAr as $keyLink => $link) {
                $itemCrawler = $this->client->request('GET', $link);
                if (!$this->isLocalMode()) {
                    $events = array_merge($events, $this->parseEvents($itemCrawler));
                } else {
                    $response = $this->client->getResponse();
                    Storage::disk('public')->put('traunstein/' . 'link_' . str_pad(($keyLink + 1), 2, '0', STR_PAD_LEFT) . '.html', $response->getContent());
                    throw new \Exception('DONE');
                }
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

    // Query to main site to get token and event_ids
    // https://irs18whl.infomax.online/traunstein/?form=search&widgetToken=VV5JP7zDQHk.&searchType=search&dateFrom=2025-11-18&dateTo=2025-12-18&timeFrom=0&latitude=47.8857&longitude=12.6389&location=Traunstein&distance=50#-IMXEVENT-results
    protected function parseMainForm() {
        // Query to main site to get token and event_ids :START
        $crawler = $this->client->request('GET', $this->parseConfig['url']);
        if ($this->isLocalMode()) {
            $response = $this->client->getResponse();
            Storage::disk('public')->put('traunstein/traunstein.html', $response->getContent());
        }

        $scriptText = $crawler->filter('script')->eq(2)->text();
        // token: 'fFSSsbkAaQ4.'
        $checkToken = preg_match('#token: \'(.+?)\'#', $scriptText, $matches);
        if ($checkToken) {
            $this->token = $matches[1];
        } else {
            throw new \Exception('Can\'t find token');
        }

        $eventIdsContainer = $crawler->filter('#-IMXEVENT-results');
        $eventsListContainer = $eventIdsContainer->filter('div.-IMXEVNT-h-grid.-IMXEVNT-h-grid--fixed.-IMXEVNT-h-grid--margins');

        return $eventsListContainer;
    }

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
            $eventTitleId = $this->getEventTitleId($title);

            $category = $this->filterNodeText($node, $sourceParse['category_selector']);
            $infoText = $this->filterNodeText($node, $sourceParse['info_selector']);
            $description = $this->filterNodeText($node, $sourceParse['description_selector']);
            $dates = $this->parseDateTime($infoText);

            $location = $this->parseLocation($infoText);
            // Removing city from location
            $city = $this->parseCity($location);

            $eventTypes = $this->determineEventTypes($category, $title, $description);
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
                'region' => $this->parseConfig['region'],
                'city' => $city,
                'event_types' => $eventTypes ? json_encode($eventTypes, JSON_UNESCAPED_UNICODE) : null,
                'source' => $this->source,
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
            // $this->log($node->html());
            // $this->log($e->getTraceAsString());

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

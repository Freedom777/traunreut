<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventTitle;
use Exception;
use Illuminate\Support\Str;
use Symfony\Component\DomCrawler\Crawler;

class K1Controller extends BaseParserController
{
    protected string $configPath = 'parse.k1';

    public function fetchEvents(): array
    {
        $crawler = $this->client->request('GET', $this->parseConfig['url']);

        if ($this->parseConfig['parse']['start_block_selector']) {
            $crawler = $crawler->filter($this->parseConfig['parse']['start_block_selector']);
        }

        return $this->parseEvents($crawler);
    }

    protected function parseEventNode(Crawler $node): ?array
    {
        try {
            ++$this->eventCount;
            $link = $this->cleanText($node->filter('a')->last()->attr('href'));
            $eventId = (int) Str::afterLast($link, '/');

            if ($this->checkExistEventById($eventId)) {
                ++$this->duplicateCount;
                return null;
            }

            $sourceParse = $this->parseConfig['parse'];

            $title = $this->cleanText($node->attr($sourceParse['title_selector']));
            $date = $this->cleanText($node->attr($sourceParse['date_selector']));
            $customDataSelector = 'p.text-gray-600.leading-relaxed';
            $customData = $node->filter($customDataSelector);
            // 30.12.2025 | 16:00 Uhr
            $timeAr = explode(' ', $this->cleanText($customData->eq(0)->text()));
            $time = '';
            if (isset($timeAr[2])) {
                $time = $timeAr[2];
            }
            $dates = $this->parseDateTime($date . ' / ' . $time);
            $startDate = $dates['start'];

            $cityId = $this->getCityId($this->parseConfig['city']);

            if ($this->checkExistByDateTitleCity($startDate, $title, $cityId)) {
                $eventTitleId = EventTitle::where('title_de', $title)->value('id');
                if ($eventTitleId) {
                    $count = Event::where(['start_date' => $startDate, 'event_title_id' => $eventTitleId, 'city_id' => $cityId])->delete();
                    $this->duplicateCount += $count;
                }
            }

            $eventTitleId = $this->getEventTitleId($title);
            $artist = $this->cleanText($node->attr($sourceParse['artist_selector']));
            $category = $this->cleanText($node->attr($sourceParse['category_selector']));
            $location = $this->cleanText($customData->eq(2)->text());
            $img = $node->filter('img')->first();
            $imgSrc = $this->cleanText($img->attr('src'));
            $currentDate = date('Y-m-d H:i:s');
            $eventTypeIds = $this->determineEventTypes($category, $title, '');

            $eventRec = [
                // 'title' => $title,
                'event_title_id' => $eventTitleId,
                'site' => $this->parseConfig['site'],
                'event_id' => $eventId,
                'category' => $category,
                'artist' => $artist,
                'img' => $imgSrc,
                'start_date' => $startDate,
                'end_date' => null,
                'location' => $location,
                'link' => $link,
                'city_id' => $cityId,
                'event_type_ids' => $eventTypeIds,
                'source' => $this->parseConfig['site'],
                'description' => null,
                'price' => null,
                'is_active' => 1,
                'debug_html' => $node->html(),
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
                'deleted_at' => null,
            ];

            $this->processedIdEvents[] = $eventId;
            ++$this->successCount;

            return $eventRec;

        } catch (Exception $e) {
            ++$this->errorCount;
            $this->log('Ошибка при обработке события: ' . $e->getMessage(), 'ERROR');
            return null;
        }
    }
}

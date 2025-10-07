<?php

namespace App\Http\Controllers;

use App\Models\Event;
use DateTime;
use Exception;
use Illuminate\Support\Str;
use Symfony\Component\DomCrawler\Crawler;

class ParseK1Controller extends BaseParserController
{
    public function run($source)
    {
        $this->log('Начинаем парсинг: ' . $source['url']);

        try {
            $crawler = $this->client->request('GET', $source['url']);

            if ($source['parse']['start_block_selector']) {
                $crawler = $crawler->filter($source['parse']['start_block_selector']);
            }

            $events = $crawler->filter($source['parse']['event_list_selector']);
            $events->each(function (Crawler $event) use ($source) {
                try {
                    $sourceParse = $source['parse'];
                    $this->eventCount++;
                    $title = $this->cleanText($event->attr($sourceParse['title_selector']));
                    $artist = $this->cleanText($event->attr($sourceParse['artist_selector']));
                    $date = $this->cleanText($event->attr($sourceParse['date_selector']));
                    $category = $this->cleanText($event->attr($sourceParse['category_selector']));

                    $customDataSelector = 'p.text-gray-600.leading-relaxed';
                    $customData = $event->filter($customDataSelector);
                    $location = $this->cleanText($customData->eq(2)->text());
                    $time = $this->cleanText($customData->eq(0)->text());

                    $checkTime = preg_match('#(\d{1,2}:\d{2}) Uhr#', $time, $matches);
                    if ($checkTime) {
                        $date .= ' ' . $matches[1];
                        $dt = DateTime::createFromFormat('d.m.Y H:i', $date);
                    } else {
                        $dt = DateTime::createFromFormat('d.m.Y', $date);
                    }
                    $img = $event->filter('img')->first();
                    $imgSrc = $this->cleanText($img->attr('src'));
                    $link = $this->cleanText($event->filter('a')->last()->attr('href'));
                    $eventId = (int) Str::afterLast($link, '/');
                    $currentDate = date('Y-m-d H:i:s');

                    $eventRec = [
                        'site' => $source['site'],
                        'event_id' => $eventId,
                        'category' => $category,
                        'artist' => $artist,
                        'title' => $title,
                        'img' => $imgSrc,
                        'start_date' => $dt ? $dt->format('Y-m-d H:i:s') : NULL,
                        'end_date' => null,
                        'location' => $location,
                        'link' => $link,
                        'region' => $source['region'],
                        'city' => $source['city'],
                        'event_types' => json_encode($source['event_types'], JSON_UNESCAPED_UNICODE),
                        'source' => $source['url'],
                        'description' => null,
                        'price' => null, // В данном HTML нет информации о ценах
                        'is_active' => 1,
                        'created_at' => $currentDate,
                        'updated_at' => $currentDate,
                    ];

                    if ($this->isEventDuplicate($eventRec)) {
                        ++$this->duplicateCount;
                        $this->log('Дубликат события: ' . $title, 'DEBUG');
                    } else {
                        Event::insert($eventRec);
                        ++$this->successCount;

                        $this->log('Добавлено событие: ' . $title . ' (' . $date . ')');
                    }
                } catch (Exception $e) {
                    ++$this->errorCount;
                    $this->log('Ошибка при обработке события: ' . $e->getMessage(), 'ERROR');
                }
            });
        } catch (Exception $e) {
            $this->log('Критическая ошибка при парсинге ' . $source['url'] . ': ' . $e->getMessage(), 'ERROR');
        }

        $this->log('Завершен парсинг ' . $source['url'] . ': найдено ' . $this->eventCount . ', добавлено ' . $this->successCount .
            ', дубликатов ' . $this->duplicateCount . ', ошибок ' . $this->errorCount);
    }

}

<?php

namespace App\Http\Controllers;

use App\Models\Event;
use DateTime;
use Exception;
use Illuminate\Support\Str;
use Symfony\Component\DomCrawler\Crawler;

class ParseK1Controller extends BaseParserController
{
    public function run()
    {
        $this->log('Начинаем парсинг: ' . $this->parseConfig['url']);

        try {
            $crawler = $this->client->request('GET', $this->parseConfig['url']);

            if ($this->parseConfig['parse']['start_block_selector']) {
                $crawler = $crawler->filter($this->parseConfig['parse']['start_block_selector']);
            }

            $events = $crawler->filter($this->parseConfig['parse']['event_list_selector']);
            $events->each(function (Crawler $event) {
                try {
                    ++$this->eventCount;
                    $link = $this->cleanText($event->filter('a')->last()->attr('href'));
                    $eventId = (int) Str::afterLast($link, '/');
                    if ($this->checkExistEvent($eventId)) {
                        ++$this->duplicateCount;
                        return null;
                    }

                    $sourceParse = $this->parseConfig['parse'];

                    $title = $this->cleanText($event->attr($sourceParse['title_selector']));
                    $events_ru_id = $this->getEventRuIdByTitle($title);
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

                    $currentDate = date('Y-m-d H:i:s');

                    $eventRec = [
                        'events_ru_id' => $events_ru_id,
                        'site' => $this->parseConfig['site'],
                        'event_id' => $eventId,
                        'category' => $category,
                        'artist' => $artist,
                        'img' => $imgSrc,
                        'start_date' => $dt ? $dt->format('Y-m-d H:i:s') : NULL,
                        'end_date' => null,
                        'location' => $location,
                        'link' => $link,
                        'region' => $this->parseConfig['region'],
                        'city' =>$this->parseConfig['city'],
                        'event_types' => json_encode($this->parseConfig['event_types'], JSON_UNESCAPED_UNICODE),
                        'source' => $this->parseConfig['url'],
                        'description' => null,
                        'price' => null, // В данном HTML нет информации о ценах
                        'is_active' => 1,
                        'debug_html' => $event->html(),
                        'created_at' => $currentDate,
                        'updated_at' => $currentDate,
                    ];

                    Event::insert($eventRec);
                    ++$this->successCount;
                } catch (Exception $e) {
                    ++$this->errorCount;
                    $this->log('Ошибка при обработке события: ' . $e->getMessage(), 'ERROR');
                }
            });
        } catch (Exception $e) {
            $this->log('Критическая ошибка при парсинге ' . $this->parseConfig['url'] . ': ' . $e->getMessage(), 'ERROR');
        }

        $this->log('Завершен парсинг ' . $this->parseConfig['url'] . ': найдено ' . $this->eventCount . ', добавлено ' . $this->successCount .
            ', дубликатов ' . $this->duplicateCount . ', ошибок ' . $this->errorCount);
    }

}

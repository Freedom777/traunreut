<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventTitle;
use App\Models\EventType;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\HttpClient\HttpClient;
use Throwable;

class NaturefreundeController extends BaseParserController
{
    protected string $configPath = 'parse.naturefreunde';

    private string $category = 'Naturerlebnisse';

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
            // ++$this->eventCount;
            // $eventId = (int) Str::afterLast($link, '/');
            // dd($node->html());
            $sourceParse = $this->parseConfig['parse'];
            $dateSrc = $this->filterNodeText($node, $sourceParse['date_selector']);
            if (!$dateSrc) {
                $this->log('Невозможно прочесть дату события.');
                return null;
            } else {
                $startDate = $this->parseDeutschDate($dateSrc);
                if (!$startDate) {
                    $this->log('Невозможно определить формат даты события (' . $dateSrc . ').');
                    return null;
                } else {
                    $eventId = str_replace('-', '', $startDate);
                    if ($this->checkExistEventById($eventId)) {
                        ++$this->duplicateCount;
                        return null;
                    }
                    $title = $this->filterNodeText($node, $sourceParse['title_selector']);
                    $cityId = $this->getCityId($this->parseConfig['city']);
                    $eventTitleId = $this->getEventTitleId($title);
                    $currentDate = date('Y-m-d H:i:s');
                    $eventTypeIds = [EventType::where(['name' => $this->category])->value('id')];

                    $eventRec = [
                        'event_title_id' => $eventTitleId,
                        'site' => $this->parseConfig['site'],
                        'event_id' => $eventId,
                        'category' => 'Naturerlebnisse',
                        'artist' => null,
                        'img' => 'https://le-cdn.website-editor.net/s/a0f8452be095453aa9e8b886c124c303/dms3rep/multi/opt/260px-Naturfreunde-1920w.png?Expires=1769082088&Signature=GfaQGBkY2b0iWyaczuQFSCMU4rKlKwZH6tg6GDdfH2IqBRu3HHEFOKG2UtInIM-4OlIRW9iA~1ef3hCkkBaYv0uozIlCEtPUJKmAWqW70ApcKcj4ZZas2lnfuvt49iOpRlqAsg~eang5z4R8-7MD1hhQNPopR~1hwQsnlCd1bml4nC54A9Fv6G8SGQ3FQ~JCKj-NBp0Tbn0ePwAkpiP0jCWpw-2jpi50Tx9Fo2-i9zWsK2-Ci6jFnrerQLlyD~SxNQqMyl66rX1QQFFWx2Jt5JQax7PrCniof~4Sh7tilyzPBpLtoaElKL8F8IYJeLepQlw8-gf4FJONaKO45zEXHQ__&Key-Pair-Id=K2NXBXLF010TJW',
                        'start_date' => $startDate,
                        'end_date' => null,
                        'location' => $this->parseConfig['city'],
                        'link' => $this->parseConfig['url'],
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
                }
            }



        } catch (\Exception $e) {
            ++$this->errorCount;
            $this->log('Ошибка при обработке события: ' . $e->getMessage(), 'ERROR');
            return null;
        }
    }

    private function parseDeutschDate($dateString, $format = 'Y-m-d'): ?string {
        static $months = [
            'Januar' => 1, 'Februar' => 2, 'März' => 3, 'April' => 4,
            'Mai' => 5, 'Juni' => 6, 'Juli' => 7, 'August' => 8,
            'September' => 9, 'Oktober' => 10, 'November' => 11, 'Dezember' => 12
        ];

        /*static $weekdays = [
            'Montag', 'Dienstag', 'Mittwoch', 'Donnerstag',
            'Freitag', 'Samstag', 'Sonntag'
        ];*/

        // Проверяем формат строки
        if (!preg_match('/^(\w+),\s*(\d+)\.\s*(\w+)$/', $dateString, $matches)) {
            return null; // Формат не соответствует ожидаемому
        }

        if (!isset($months[$matches[3]])) {
            return null;
        }

        try {
            $date = Carbon::create(now()->year, $months[$matches[3]], $matches[2])->locale('de');

            if ($date->isoFormat('dddd') !== $matches[1]) {
                $date->addYear();
            }

            return $date->format($format);
        } catch (\Exception $e) {
            return null;
        }
    }
}

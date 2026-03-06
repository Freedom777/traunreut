<?php


namespace App\Http\Controllers;

use App\Models\EventType;
use Symfony\Component\DomCrawler\Crawler;

class AlpenvereinController extends BaseParserController
{
    protected string $configPath = 'parse.alpenverein';

    protected function parseEventNode(Crawler $node): ?array
    {
        try {
            ++$this->eventCount;
            $title = str_replace("\u{A0}", ' ', $node->text());

            $dateExists = preg_match('#(\w+),\s*(\d{1,2})\. (' . implode('|', array_keys(self::DEUTSCH_MONTH_NAMES)) . ') (\d{4})(?: um (\d{1,2}):(\d{2}) Uhr)?#i', $title, $dateMatches);
            if (!$dateExists) {
                $this->log('Невозможно прочесть дату события.');
                return null;
            } else {
                $cityId = null;
                $address = $this->parseConfig['city'];
                if (preg_match('/\bin\s+([^,]+),\s*([A-Za-zÄÖÜäöüß\-. ]+\s+\d+[a-zA-Z]?)\b/u', $title, $m)) {
                    $city = trim($m[1]);
                    $address = trim($m[2]);
                    $cityId = $this->getCityId($city);
                }
                if (!$cityId) {
                    $cityId = $this->getCityId($this->parseConfig['city']);
                }
                $startDate = $this->parseDeutschDate($dateMatches[1] . ', ' . $dateMatches[2] . '.' . $dateMatches[3]);
                $eventId = (int) str_replace('-', '', $startDate);
                if ($this->checkExistEventById($eventId)) {
                    ++$this->duplicateCount;
                    return null;
                }

                if (isset($dateMatches[5]) && isset($dateMatches[6])) {
                    $startDate .= ' ' . $dateMatches[5] . ':' . $dateMatches[6] . ':00';
                }

                $eventTitleId = $this->getEventTitleId($title);
                $currentDate = date('Y-m-d H:i:s');
                $eventTypeIds = EventType::whereIn('name', $this->parseConfig['event_types'])->pluck('id')->toArray();

                $eventRec = [
                    'event_title_id' => $eventTitleId,
                    'site' => $this->parseConfig['site'],
                    'event_id' => $eventId,
                    'category' => $this->parseConfig['category'],
                    'artist' => null,
                    'img' => 'https://www.alpenverein-traunstein.de/themes/avts/logo.svg',
                    'start_date' => $startDate,
                    'end_date' => null,
                    'location' => $address,
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
        } catch (\Exception $e) {
            ++$this->errorCount;
            $this->log('Ошибка при обработке события: ' . $e->getMessage(), 'ERROR');
            return null;
        }
    }
}

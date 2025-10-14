<?php

namespace App\Console\Commands;

use App\Http\Controllers\ParseK1Controller;
use App\Http\Controllers\TraunreutController;
use App\Models\Event;
use App\Models\EventRu;
use Carbon\CarbonImmutable;
use Illuminate\Console\Command;

class ParseCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'parse:site {--site=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parse event sites';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Создаем фиктивный экземпляр запроса, если контроллер ожидает его
        // $request = Request::create('/dummy-url', 'GET');
        $siteName = $this->option('site');
        $this->info('Старт парсинга, сайт ' . $siteName . '.');

        switch ($siteName) {
            case 'traunreut':
                $this->parseTraunreut();
            break;
            case 'k1':
                $this->parseK1();
            break;
            case 'all':
                $this->parseTraunreut();
                $this->parseK1();
            break;
            case 'vocabulary':
                $this->getVocabulary();
            break;
            case 'updateVocabulary':
                $this->updateVocabulary();
            break;
        }

        $this->info('Завершён парсинг, сайт ' . $siteName . '.');
    }

    protected function parseK1() {
        // Создаем экземпляр контроллера и вызываем метод
        $k1Controller = new ParseK1Controller();
        /*$source = [
            'url' => 'https://www.k1-traunreut.de/programm',
            'region' => 'Bayern',
            'site' => 'k1-traunreut.de',
            'city' => 'Traunreut',
            'event_types' => ['Konzerte', 'Theater', 'Kultur', 'Kinderveranstaltungen', 'Comedy', 'Musik'],
            'parse' => [
                'start_block_selector' => '#gefilterte-events', // div[id="gefilterte-events"]
                'event_list_selector' => 'section',
                'title_selector' => 'data-title',
                'artist_selector' => 'data-artist',
                'date_selector' => 'data-date',
                'category_selector' => 'data-cat',
            ],
        ];*/
        $k1Controller->run();
    }

    protected function parseTraunreut() {
        $traunreutController = new TraunreutController();
        /*$nowDate = CarbonImmutable::now();
        $monthLaterDate = $nowDate->addMonth();
        $source = [
            'url' => 'https://veranstaltungen.traunreut.de/traunreut/?form=search&searchType=search&dateFrom=' . $nowDate->format('Y-m-d') . '&dateTo=' . $monthLaterDate->format('Y-m-d') . '&timeFrom=0&latitude=47.955&longitude=12.5715&location=Traunreut&distance=50',
            'region' => 'Bayern',
            'site' => 'traunreut.de',
            // 'city' => 'Traunreut',
            // 'event_types' => ['Konzerte', 'Theater', 'Kultur', 'Kinderveranstaltungen', 'Comedy', 'Musik'],
            'parse' => [
                'event_list_selector' => 'article.-IMXEVNT-listElement',
                'title_selector' => '.-IMXEVNT-seoTitle',
                'category_selector' => '.-IMXEVNT-listElement__text__subline',
                'info_selector' => '.-IMXEVNT-listElement__text__info',
                'description_selector' => '.-IMXEVNT-listElement__text__extended p',


                'content_block_selector' => 'div.-IMXEVENT-lazyLoadList__page',
                'item_selector' => 'article.-IMXEVNT-listElement',
            ],
        ];*/
        $traunreutController->run();
    }

    protected function getVocabulary() {
        $eventTitles = Event::distinct()->get(['title']);
        foreach ($eventTitles as $eventTitle) {
            file_put_contents('vocabulary.txt', $eventTitle->title . PHP_EOL, FILE_APPEND);
        }
    }

    protected function updateVocabulary()
    {
        $eventTitles = Event::distinct()->get(['id', 'title']);
        $vocDeutschAr = file('vocabulary.txt', FILE_SKIP_EMPTY_LINES | FILE_IGNORE_NEW_LINES);
        $vocRussianAr = file('vocabulary_ru.txt', FILE_SKIP_EMPTY_LINES | FILE_IGNORE_NEW_LINES);
        $vocabularyAr = array_combine($vocDeutschAr, $vocRussianAr);
        $insertDataAr = [];
        $nowDate = CarbonImmutable::now()->format('Y-m-d H:i:s');
        foreach ($eventTitles as $eventTitle) {
            $insertDataAr[] = [
                'id' => $eventTitle->id,
                'title' => $vocabularyAr[$eventTitle->title],
                'created_at' => $nowDate,
                'updated_at' => $nowDate,
            ];
        }
        EventRu::insert($insertDataAr);
    }
}

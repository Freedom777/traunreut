<?php

namespace App\Console\Commands;

use App\Models\EventTitle;
use Illuminate\Console\Command;

class ParseCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'parse:site {--site=} {--local} {--debug}';

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
            case 'naturefreunde':
            case 'k1':
                $this->startParse($siteName);
            break;
            case 'all':
                $this->startParse('traunreut');
                $this->startParse('k1');
                $this->startParse('naturefreunde');
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

    private function startParse(string $controllerName) {
        $cname = ucfirst($controllerName) . 'Controller';
        $class = 'App\\Http\\Controllers\\' . $cname;
        $controller = app($class);
        if ($this->option('local')) {
            $controller->setLocalMode(true);
        }
        if ($this->option('debug')) {
            $controller->setDebugMode(true);
        }
        $controller->run();
    }

    protected function getVocabulary() {
        $eventTitles = EventTitle::distinct()->get(['title_de']);
        foreach ($eventTitles as $eventTitle) {
            file_put_contents('vocabulary.txt', $eventTitle->title_de . PHP_EOL, FILE_APPEND);
        }
    }

    protected function updateVocabulary()
    {
        $eventTitles = EventTitle::all();
        $vocDeutschAr = file('vocabulary.txt', FILE_SKIP_EMPTY_LINES | FILE_IGNORE_NEW_LINES);
        $vocRussianAr = file('vocabulary_ru.txt', FILE_SKIP_EMPTY_LINES | FILE_IGNORE_NEW_LINES);
        // Ensure arrays are valid
        if (!$vocDeutschAr || !$vocRussianAr) {
            $this->error('Vocabulary files empty or missing');
            return;
        }
        $vocabularyAr = array_combine($vocDeutschAr, $vocRussianAr);

        foreach ($eventTitles as $eventTitle) {
            if (isset($vocabularyAr[$eventTitle->title_de])) {
                $eventTitle->title_ru = $vocabularyAr[$eventTitle->title_de];
                $eventTitle->save();
            }
        }
    }
}

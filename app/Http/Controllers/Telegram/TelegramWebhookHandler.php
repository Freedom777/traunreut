<?php

namespace App\Http\Controllers\Telegram;

use App\Models\City;
use App\Models\Event;
use Carbon\Carbon;
use DefStudio\Telegraph\Handlers\WebhookHandler;
use DefStudio\Telegraph\Keyboard\Button;
use DefStudio\Telegraph\Keyboard\Keyboard;
use DefStudio\Telegraph\Keyboard\ReplyButton;
use DefStudio\Telegraph\Keyboard\ReplyKeyboard;
use DefStudio\Telegraph\Models\TelegraphBot;
use Illuminate\Support\Stringable;

class TelegramWebhookHandler extends WebhookHandler
{
    private const MAX_MESSAGE_LENGTH = 4000;
    private const ALL_CITIES = 'all';

    protected function setupChat(): void
    {
        // Получаем бота
        $token = request()->route('token');
        $this->bot = TelegraphBot::where('token', $token)->firstOrFail();

        // Вызываем родительский метод, который создаст чат
        parent::setupChat();
    }

    /**
     * Команда /start
     */
    public function start(): void
    {
        $this->sendMainMenu();
    }

    /**
     * Обработка текстовых сообщений
     */
    protected function handleChatMessage(Stringable $text): void
    {
        match ($text->toString()) {
            'Старт', '/start', '◀️ Назад' => $this->sendMainMenu(),
            '📅 По дате' => $this->sendDateMenu(),
            '🏙️ По городу' => $this->sendCityList(),
            'Сегодня' => $this->sendEventsWithCityFilter('today'),
            'Завтра' => $this->sendEventsWithCityFilter('tomorrow'),
            'Неделя' => $this->sendEventsWithCityFilter('week'),
            default => $this->sendWelcomeMessage(),
        };
    }

    public function nextPage(): void
    {
        $type = $this->data->get('type'); // 'city' или 'period'
        $page = (int) $this->data->get('page');

        if ($type === 'city') {
            $city = $this->data->get('city');
            $this->sendEventsByCity($city, $page);
        } else {
            $period = $this->data->get('period');
            $city = $this->data->get('city');
            $showAll = $city === self::ALL_CITIES;
            $this->sendEventsWithCityFilter($period, $city, $showAll, $page);
        }
    }

    /**
     * Обработка callback кнопки "Назад"
     */
    public function back(): void
    {
        $this->sendMainMenu();
    }

    /**
     * Обработка выбора режима "По дате"
     */
    public function modeByDate(): void
    {
        $this->sendDateMenu();
    }

    /**
     * Обработка выбора режима "По городу"
     */
    public function modeByCity(): void
    {
        $this->sendCityList();
    }

    /**
     * Обработка выбора конкретного города
     */
    public function selectCity(): void
    {
        $city = $this->data->get('city');
        $this->sendEventsByCity($city);
    }

    /**
     * Обработка выбора периода с фильтром города
     */
    public function filterPeriod(): void
    {
        $period = $this->data->get('period');
        $city = $this->data->get('city');
        $showAll = $city === self::ALL_CITIES;

        $this->sendEventsWithCityFilter($period, $city, $showAll);
    }

    /**
     * Приветственное сообщение
     */
    private function sendWelcomeMessage(): void
    {
        $keyboard = ReplyKeyboard::make()
            ->row([
                ReplyButton::make('Старт'),
            ])
            ->resize();

        $this->chat->message('Для начала работы нажмите кнопку Старт')
            ->replyKeyboard($keyboard)
            ->send();
    }

    /**
     * Главное меню
     */
    private function sendMainMenu(): void
    {
        $keyboard = ReplyKeyboard::make()
            ->row([
                ReplyButton::make('📅 По дате'),
                ReplyButton::make('🏙️ По городу'),
            ])
            ->resize();

        $this->chat->message('Выберите способ поиска событий:')
            ->replyKeyboard($keyboard)
            ->send();
    }

    /**
     * Меню выбора даты
     */
    private function sendDateMenu(): void
    {
        $keyboard = ReplyKeyboard::make()
            ->row([
                ReplyButton::make('◀️ Назад'),
                ReplyButton::make('Сегодня'),
                ReplyButton::make('Завтра'),
                ReplyButton::make('Неделя'),
            ])
            ->resize();

        $this->chat->message('Выберите период для просмотра событий:')
            ->replyKeyboard($keyboard)
            ->send();
    }

    /**
     * Список всех городов
     */
    private function sendCityList(): void
    {
        try {
            $cities = Event::join('cities', 'events.city_id', '=', 'cities.id')
                ->select(['cities.name as city_name', 'cities.id as city_id'])
                ->distinct()
                ->whereNotNull('events.city_id')
                ->orderBy('cities.name', 'asc')
                ->get();

            if ($cities->isEmpty()) {
                $this->chat->message('Города не найдены в базе данных.')
                    ->keyboard(Keyboard::make()->button('◀️ Назад')->action('back'))
                    ->send();
                return;
            }

            // Формируем inline кнопки
            $keyboard = Keyboard::make();
            $row = [];

            foreach ($cities as $cityData) {
                $row[] = Button::make($cityData->city_name)
                    ->action('selectCity')
                    ->param('city', $cityData->city_id);

                if (count($row) === 2) {
                    $keyboard->row($row);
                    $row = [];
                }
            }

            if (!empty($row)) {
                $keyboard->row($row);
            }

            // Добавляем кнопку "Назад"
            $keyboard->button('◀️ Назад')->action('back');

            $this->chat->message('Выберите город:')
                ->keyboard($keyboard)
                ->send();

        } catch (\Exception $e) {
            \Log::error('sendCityList error', ['error' => $e->getMessage()]);
            $this->chat->message('Ошибка при получении списка городов.')
                ->send();
        }
    }

    /**
     * События по выбранному городу
     */
    private function sendEventsByCity(string $city, int $page = 1): void
    {
        $languageCode = $this->message?->from()?->languageCode() ?? 'ru';
        Carbon::setLocale($languageCode);

        $now = Carbon::now();
        $startDate = $now->copy()->startOfDay();

        $query = Event::with(['eventTitle', 'city'])
            ->where('start_date', '>=', $startDate)
            ->orderBy('start_date', 'asc');

        if ($city !== self::ALL_CITIES) {
            $query->where('city_id', $city);
        }

        $events = $query->get();

        $cityName = $city == self::ALL_CITIES
            ? 'городах'
            : ('городе ' . ($events->first()->city->name ?? City::find($city)->name ?? 'неизвестном'));

        if ($events->isEmpty()) {
            $this->chat->message('События в ' . $cityName . ' не найдены.')
                ->keyboard(Keyboard::make()->button('◀️ Назад')->action('back'))
                ->send();
            return;
        }

        // Формируем сообщения с событиями
        $result = $this->formatEventsMessages(
            $events,
            'События в ' . $cityName,
            $languageCode,
            $page,
            false  // НЕ показываем город в событиях (режим "по городу")
        );

        $this->sendPaginatedMessage(
            $result['message'],
            $result['hasMore'],
            [
                'type' => 'city',
                'city' => $city,
                'page' => $page + 1
            ]
        );
    }

    /**
     * События с фильтром по городу и периоду
     */
    private function sendEventsWithCityFilter(
        string $period,
        ?string $city = null,
        bool $showAll = false,
        int $page = 1
    ): void {
        $languageCode = $this->message?->from()?->languageCode() ?? 'ru';
        Carbon::setLocale($languageCode);

        $now = Carbon::now();

        [$startDate, $endDate] = match ($period) {
            'tomorrow' => [
                $now->copy()->addDay()->startOfDay(),
                $now->copy()->addDay()->endOfDay()
            ],
            'week' => [
                $now->copy()->startOfDay(),
                $now->copy()->addDays(7)->endOfDay()
            ],
            default => [
                $now->copy()->startOfDay(),
                $now->copy()->endOfDay()
            ]
        };

        $events = Event::with(['eventTitle', 'city'])
            ->whereBetween('start_date', [$startDate, $endDate])
            ->orderBy('start_date', 'asc')
            ->get();

        if ($events->isEmpty()) {
            $this->chat->message('События не найдены.')
                ->keyboard(Keyboard::make()->button('◀️ Назад')->action('back'))
                ->send();
            return;
        }

        // Filter by city
        if ($city && $city !== self::ALL_CITIES) {
            $events = $events->where('city_id', $city);
        }

        $cities = $events->map(fn($e) => ['id' => $e->city_id, 'name' => $e->city?->name])
            ->unique('id')
            ->sortBy('name')
            ->values()
            ->toArray();

        // Если несколько городов и не выбрано "показать все"
        if (!$showAll && count($cities) > 1) {
            $keyboard = Keyboard::make();

            // Кнопка "Все города"
            $keyboard->button('🌍 Все города')
                ->action('filterPeriod')
                ->param('period', $period)
                ->param('city', self::ALL_CITIES);

            // Кнопки городов
            $row = [];
            foreach ($cities as $c) {
                $row[] = Button::make($c['name'])
                    ->action('filterPeriod')
                    ->param('period', $period)
                    ->param('city', $c['id']);

                if (count($row) === 2) {
                    $keyboard->row($row);
                    $row = [];
                }
            }

            if (!empty($row)) {
                $keyboard->row($row);
            }

            $keyboard->button('◀️ Назад')->action('back');

            $this->chat->message('Выберите город или посмотрите все события:')
                ->keyboard($keyboard)
                ->send();
            return;
        }

        // Формируем заголовок периода (БЕЗ даты, только описание периода)
        $cityTitle = $city;
        if ($city && $city !== self::ALL_CITIES) {
            $cityTitle = $events->first()?->city?->name ?? City::find($city)?->name ?? $city;
        }

        $titleDate = $this->formatPeriodTitle($startDate, $endDate, $cityTitle, $languageCode, false);

        $result = $this->formatEventsMessages(
            $events,
            $titleDate,
            $languageCode,
            $page,
            true  // Показываем город жирным в событиях (режим "по дате")
        );

        $this->sendPaginatedMessage(
            $result['message'],
            $result['hasMore'],
            [
                'type' => 'period',
                'period' => $period,
                'city' => $city ?? self::ALL_CITIES,
                'page' => $page + 1
            ]
        );
    }

    /**
     * Формирование заголовка периода
     */
    private function formatPeriodTitle(
        Carbon $startDate,
        Carbon $endDate,
        ?string $city,
        string $languageCode,
        bool $includeDate = true
    ): string {
        Carbon::setLocale($languageCode);

        // Для режима "по дате" не добавляем дату в заголовок (она будет в теле)
        if (!$includeDate) {
            $title = 'События';

            if (!$startDate->isSameDay($endDate)) {
                $title .= ' на период';
            }

            if ($city && $city !== self::ALL_CITIES) {
                $title .= ' (' . $city . ')';
            }

            return $title;
        }

        // Для режима "по городу" оставляем дату в заголовке
        if ($startDate->isSameDay($endDate)) {
            $title = $startDate->translatedFormat('d.m.Y (l)');
        } else {
            $title = 'События на период ' .
                $startDate->translatedFormat('d.m.Y (l)') .
                ' - ' .
                $endDate->translatedFormat('d.m.Y (l)');
        }

        if ($city && $city !== self::ALL_CITIES) {
            $title .= ' (' . $city . ')';
        }

        return $title;
    }

    private function formatEventsMessages($events, string $title, string $languageCode = 'ru', int $page = 1, bool $showCityInEvents = false): array
    {
        Carbon::setLocale($languageCode);

        $header = $this->buildMessageHeader($events, $title, $page);
        $eventsByDate = $events->groupBy(fn($e) => Carbon::parse($e->start_date)->format('Y-m-d'));

        return $this->buildPageDynamically($eventsByDate, $header, $page, $languageCode, $showCityInEvents);
    }

    private function buildPageDynamically($eventsByDate, string $baseHeader, int $targetPage, string $languageCode, bool $showCityInEvents): array
    {
        $currentPageNum = 1;
        $currentMessage = $baseHeader;
        $lastDate = null;
        $lastTime = null;
        $foundTargetPage = false;

        foreach ($eventsByDate as $dateKey => $dateEvents) {
            $date = Carbon::parse($dateKey);
            $dateHeader = '<b>' . $date->translatedFormat('d.m.Y (l)') . '</b>';

            $eventsByTime = $dateEvents->groupBy(fn($e) => Carbon::parse($e->start_date)->format('H:i'));

            foreach ($eventsByTime as $time => $timeEvents) {
                $timeStr = $time === '00:00' ? 'Весь день' : $time;
                $timeBlock = '<b>' . $timeStr . '</b>';

                foreach ($timeEvents as $event) {
                    $line = $this->formatSingleEvent($event, $showCityInEvents);

                    // Формируем добавление
                    $addition = $this->buildEventAddition($dateHeader, $timeBlock, $line, $lastDate, $lastTime);

                    // Проверяем влезет ли
                    $wouldFit = mb_strlen($currentMessage . $addition) <= self::MAX_MESSAGE_LENGTH;

                    if (!$wouldFit) {
                        // Не влезает
                        if ($currentPageNum === $targetPage) {
                            // Это наша страница и мы уже что-то добавили
                            return [
                                'message' => trim($currentMessage),
                                'hasMore' => true
                            ];
                        }

                        // Переходим на новую страницу
                        $currentPageNum++;
                        $currentMessage = $baseHeader;
                        $lastDate = null;
                        $lastTime = null;

                        // Добавляем это событие на новую страницу
                        $addition = $this->buildEventAddition($dateHeader, $timeBlock, $line, $lastDate, $lastTime);
                        $currentMessage .= $addition;
                    } else {
                        // Влезает - добавляем
                        $currentMessage .= $addition;

                    }

                    if ($currentPageNum === $targetPage) {
                        $foundTargetPage = true;
                    }

                    // Если мы прошли целевую страницу - можем остановиться
                    if ($currentPageNum > $targetPage) {
                        return [
                            'message' => trim($currentMessage),
                            'hasMore' => false
                        ];
                    }
                }
            }
        }

        // Закончились все события
        if ($currentPageNum === $targetPage || $foundTargetPage) {
            return [
                'message' => trim($currentMessage),
                'hasMore' => false
            ];
        }

        // Запрошенная страница не существует
        return [
            'message' => $baseHeader . PHP_EOL . PHP_EOL . 'Страница не найдена',
            'hasMore' => false
        ];
    }

    private function buildEventAddition(string $dateHeader, string $timeBlock, string $eventLine, ?string &$lastDate, ?string &$lastTime): string
    {
        $addition = '';

        if ($lastDate !== $dateHeader) {
            $addition .= PHP_EOL . PHP_EOL . $dateHeader;
            $lastDate = $dateHeader;
            $lastTime = null; // сбрасываем время при смене даты
        }

        if ($lastTime !== $timeBlock) {
            $addition .= PHP_EOL . $timeBlock;
            $lastTime = $timeBlock;
        }

        $addition .= PHP_EOL . ' ' . $eventLine;

        return $addition;
    }

    private function buildMessageHeader($events, string $title, int $page): string
    {
        $sites = $events->pluck('site')->filter()->unique();

        if ($sites->isNotEmpty()) {
            $links = $sites->map(fn ($site) =>
                '<a href="http://' . htmlspecialchars($site) . '">' . htmlspecialchars($site) . '</a>'
            )->implode(', ');

            $title .= PHP_EOL . ($sites->count() > 1 ? 'Источники: ' : 'Источник: ') . $links;
        }

        $header = '<b>' . $title . '</b>';
        if ($page > 1) {
            $header .= ' (стр. ' . $page . ')';
        }

        return $header;
    }

    private function formatSingleEvent($event, bool $showCityInEvents): string
    {
        $titleRu = $event->eventTitle?->title_ru ?? $event->eventTitle?->title_de;

        // Формируем локацию
        if ($showCityInEvents) {
            $cityName = $event->city?->name ?? '';
            $location = $event->location
                ? htmlspecialchars($event->location) . ', <b>' . htmlspecialchars($cityName) . '</b>'
                : '<b>' . htmlspecialchars($cityName) . '</b>';
        } else {
            $location = htmlspecialchars($event->location ?: ($event->city?->name ?? ''));
        }

        // Формируем строку события
        $line = '';
        if ($event->site === 'naturfreunde-traunreut.de') {
            $line .= 'Naturfreunde: ';
        }

        $line .= $event->link
            ? '<a href="' . htmlspecialchars($event->link) . '">' . htmlspecialchars($titleRu) . '</a> (' . $location . ')'
            : htmlspecialchars($titleRu) . ' (' . $location . ')';

        return $line;
    }

    /**
     * Отправка множественных сообщений
     */
    private function sendMessages(array $messages): void
    {
        $messageCount = count($messages);

        foreach ($messages as $index => $messageText) {
            // На последнем сообщении добавляем кнопку "Назад"
            if ($index === $messageCount - 1) {
                $keyboard = Keyboard::make()
                    ->button('◀️ Назад')
                    ->action('back');

                $this->chat->message($messageText)
                    ->keyboard($keyboard)
                    ->send();
            } else {
                $this->chat->message($messageText)->send();
            }

            if ($index < $messageCount - 1) {
                usleep(100000);
            }
        }
    }

    private function sendPaginatedMessage(string $message, bool $hasMore, array $nextPageParams): void
    {
        $keyboard = Keyboard::make();
        $page = $nextPageParams['page'] - 1; // Текущая страница

        $navButtons = [];

        // Кнопка "Предыдущая" если не первая страница
        if ($page > 1) {
            $navButtons[] = Button::make('◀️ Предыдущая')
                ->action('prevPage')
                ->param('type', $nextPageParams['type'])
                ->param('page', $page - 1);

            if ($nextPageParams['type'] === 'city') {
                $navButtons[0]->param('city', $nextPageParams['city']);
            } else {
                $navButtons[0]->param('period', $nextPageParams['period'])
                    ->param('city', $nextPageParams['city']);
            }
        }

        // Кнопка "Следующая" если есть еще страницы
        if ($hasMore) {
            $nextButton = Button::make('Следующая ▶️')
                ->action('nextPage')
                ->param('type', $nextPageParams['type'])
                ->param('page', $nextPageParams['page']);

            if ($nextPageParams['type'] === 'city') {
                $nextButton->param('city', $nextPageParams['city']);
            } else {
                $nextButton->param('period', $nextPageParams['period'])
                    ->param('city', $nextPageParams['city']);
            }

            $navButtons[] = $nextButton;
        }

        // Добавляем кнопки навигации если они есть
        if (!empty($navButtons)) {
            $keyboard->row($navButtons);
        }

        // Кнопка "Назад" всегда в отдельной строке
        $keyboard->button('◀️ Назад')->action('back');

        $this->chat->message($message)
            ->keyboard($keyboard)
            ->send();
    }

    public function prevPage(): void
    {
        $type = $this->data->get('type');
        $page = (int) $this->data->get('page');

        if ($type === 'city') {
            $city = $this->data->get('city');
            $this->sendEventsByCity($city, $page);
        } else {
            $period = $this->data->get('period');
            $city = $this->data->get('city');
            $showAll = $city === self::ALL_CITIES;
            $this->sendEventsWithCityFilter($period, $city, $showAll, $page);
        }
    }
}

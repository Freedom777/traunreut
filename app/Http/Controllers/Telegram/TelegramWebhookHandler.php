<?php

namespace App\Http\Controllers\Telegram;

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
            'Старт', '/start' => $this->sendMainMenu(),
            '📅 По дате' => $this->sendDateMenu(),
            '🏙️ По городу' => $this->sendCityList(),
            'Сегодня' => $this->sendEventsWithCityFilter('today'),
            'Завтра' => $this->sendEventsWithCityFilter('tomorrow'),
            'Неделя' => $this->sendEventsWithCityFilter('week'),
            '◀️ Назад' => $this->sendMainMenu(),
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
            $showAll = $city === 'all';
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
        $showAll = $city === 'all';

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
            $cities = Event::select('city')
                ->distinct()
                ->whereNotNull('city')
                ->where('city', '!=', '')
                ->orderBy('city', 'asc')
                ->pluck('city')
                ->toArray();

            if (empty($cities)) {
                $this->chat->message('Города не найдены в базе данных.')
                    ->keyboard(Keyboard::make()->button('◀️ Назад')->action('back')->get())
                    ->send();
                return;
            }

            // Формируем inline кнопки
            $keyboard = Keyboard::make();
            $row = [];

            foreach ($cities as $city) {
                $row[] = Button::make($city)
                    ->action('selectCity')
                    ->param('city', $city);

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

        $events = Event::with('translation')
            ->where('city', $city)
            ->where('start_date', '>=', $startDate)
            ->orderBy('start_date', 'asc')
            ->get();

        if ($events->isEmpty()) {
            $this->chat->message('События в городе "' . $city . '" не найдены.')
                ->keyboard(Keyboard::make()->button('◀️ Назад')->action('back')->get())
                ->send();
            return;
        }

        // Формируем сообщения с событиями
        $result = $this->formatEventsMessages(
            $events,
            'События в городе ' . $city,
            $languageCode,
            $page
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

        // Ограничиваем количество сообщений
        // $messages = array_slice($messages, 0, self::MAX_MESSAGES_PER_CITY);

        // Отправляем сообщения
        // $this->sendMessages($messages);
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
            'today' => [
                $now->copy()->startOfDay(),
                $now->copy()->endOfDay()
            ],
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

        $events = Event::with('translation')
            ->whereBetween('start_date', [$startDate, $endDate])
            ->orderBy('start_date', 'asc')
            ->get();

        if ($events->isEmpty()) {
            $this->chat->message('События не найдены.')
                ->keyboard(Keyboard::make()->button('◀️ Назад')->action('back')->get())
                ->send();
            return;
        }

        // Фильтруем по городу
        if ($city && $city !== 'all') {
            $events = $events->where('city', $city);
        }

        $cities = $events->pluck('city')->unique()->sort()->values()->toArray();

        // Если несколько городов и не выбрано "показать все"
        if (!$showAll && count($cities) > 1) {
            $keyboard = Keyboard::make();

            // Кнопка "Все города"
            $keyboard->button('🌍 Все города')
                ->action('filterPeriod')
                ->param('period', $period)
                ->param('city', 'all');

            // Кнопки городов
            $row = [];
            foreach ($cities as $c) {
                $row[] = Button::make($c)
                    ->action('filterPeriod')
                    ->param('period', $period)
                    ->param('city', $c);

                if (count($row) === 2) {
                    $keyboard->row($row);
                    $row = [];
                }
            }

            if (!empty($row)) {
                $keyboard->row($row);
            }

            // Кнопка "Назад"
            $keyboard->button('◀️ Назад')->action('back');

            $this->chat->message('Выберите город или посмотрите все события:')
                ->keyboard($keyboard)
                ->send();
            return;
        }

        // Формируем заголовок периода
        $titleDate = $this->formatPeriodTitle($startDate, $endDate, $city, $languageCode);

        $result = $this->formatEventsMessages($events, $titleDate, $languageCode, $page);

        $this->sendPaginatedMessage(
            $result['message'],
            $result['hasMore'],
            [
                'type' => 'city',
                'city' => $city,
                'page' => $page + 1
            ]
        );

        // $this->sendMessages($messages);
    }

    /**
     * Формирование заголовка периода
     */
    private function formatPeriodTitle(
        Carbon $startDate,
        Carbon $endDate,
        ?string $city,
        string $languageCode
    ): string {
        Carbon::setLocale($languageCode);

        if ($startDate->isSameDay($endDate)) {
            $title = $startDate->translatedFormat('d.m.Y (l)');
        } else {
            $title = 'События на период ' .
                $startDate->translatedFormat('d.m.Y (l)') .
                ' - ' .
                $endDate->translatedFormat('d.m.Y (l)');
        }

        if ($city && $city !== 'all') {
            $title .= ' (' . $city . ')';
        }

        return $title;
    }

    private function formatEventsMessages($events, string $title, string $languageCode = 'ru', int $page = 1): array
    {
        Carbon::setLocale($languageCode);

        // Получаем первое событие для источника (все события в один день из одного источника)
        $firstEvent = $events->first();
        $site = $firstEvent->site ?? null;
        // Добавляем источник если есть
        if ($site) {
            $title .= PHP_EOL . 'Источник: <a href="http://' . $site . '">' . $site . '</a>' . PHP_EOL;
        }

        $currentMessage = '<b>' . $title . '</b>';
        if ($page > 1) {
            $currentMessage .= ' (стр. ' . $page . ')';
        }
        $currentMessage .= PHP_EOL;

        $eventsByDate = $events->groupBy(fn($e) => Carbon::parse($e->start_date)->format('Y-m-d'));

        // Формируем все события построчно
        $allLines = [];
        foreach ($eventsByDate as $dateKey => $dateEvents) {
            $date = Carbon::parse($dateKey);

            $dateHeader = '<b>' . $date->translatedFormat('d.m.Y (l)');
            if ($firstEvent->city) {
                $dateHeader .= ' (' . $firstEvent->city . ')';
            }
            $dateHeader .= '</b>'; // . PHP_EOL;

            $eventsByTime = $dateEvents->groupBy(fn($e) => Carbon::parse($e->start_date)->format('H:i'));

            foreach ($eventsByTime as $time => $timeEvents) {
                $timeStr = $time === '00:00' ? 'Целый день' : $time;
                $timeBlock = '<b>' . $timeStr . '</b>'; //  . PHP_EOL

                foreach ($timeEvents as $event) {
                    $loc = $event->location ?: $event->city;
                    $titleRu = $event->translation?->title ?? $event->title;
                    // '•'
                    $line = $event->link
                        ? ' <a href="' . $event->link . '">' . htmlspecialchars($titleRu) . '</a> (' . $loc . ')'
                        : ' ' . htmlspecialchars($titleRu) . ' (' . $loc . ')';

                    $allLines[] = [
                        'date' => $dateHeader,
                        'time' => $timeBlock,
                        'event' => $line
                    ];
                }
            }
        }

        // Пагинация
        $eventsPerPage = 30;
        $startIndex = ($page - 1) * $eventsPerPage;
        $endIndex = $startIndex + $eventsPerPage;

        $paginated = array_slice($allLines, $startIndex, $eventsPerPage);

        // Собираем сообщение
        $lastDate = null;
        $lastTime = null;

        foreach ($paginated as $item) {
            // Добавляем дату только если она изменилась
            if ($lastDate !== $item['date']) {
                if (mb_strlen($currentMessage . $item['date']) > self::MAX_MESSAGE_LENGTH) {
                    break;
                }
                $currentMessage .= PHP_EOL . PHP_EOL . $item['date']; // Убрали лишний \n
                $lastDate = $item['date'];
                $lastTime = null;
            }

            // Добавляем время только если оно изменилось
            if ($lastTime !== $item['time']) {
                if (mb_strlen($currentMessage . $item['time']) > self::MAX_MESSAGE_LENGTH) {
                    break;
                }
                $currentMessage .= PHP_EOL . $item['time']; // Один перенос строки
                $lastTime = $item['time'];
            }

            // Добавляем событие
            if (mb_strlen($currentMessage . $item['event']) > self::MAX_MESSAGE_LENGTH) {
                break;
            }
            $currentMessage .= $item['event'];
        }

        $hasMore = $endIndex < count($allLines);

        return [
            'message' => trim($currentMessage),
            'hasMore' => $hasMore
        ];
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
            $showAll = $city === 'all';
            $this->sendEventsWithCityFilter($period, $city, $showAll, $page);
        }
    }
}

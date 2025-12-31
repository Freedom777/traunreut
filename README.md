# Traunreut Events Parser

Система парсинга и управления событиями для региона Traunreut с поддержкой Telegram-бота.

## Требования

- PHP 8.1+
- Composer
- MySQL/MariaDB или SQLite
- Laragon (рекомендуется для Windows)

## Установка проекта

### 1. Клонирование и установка зависимостей

```bash
git clone <repository-url> traunreut
cd traunreut
composer install
```

### 2. Настройка окружения

Скопируйте файл окружения и настройте параметры:

```bash
copy .env.example .env
```

Отредактируйте `.env`:
```env
APP_NAME="Traunreut Events"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=traunreut
DB_USERNAME=root
DB_PASSWORD=

# Telegram Bot (опционально)
TELEGRAM_BOT_TOKEN=your_bot_token_here
```

Сгенерируйте ключ приложения:
```bash
php artisan key:generate
```

Создание ссылки на storage:
```bash
php artisan storage:link
```

### 3. Настройка базы данных

#### Создание таблиц

```bash
php artisan migrate
```

#### Заполнение базовых данных

```bash
# Заполнить штаты и типы событий
php artisan db:seed
```

#### Импорт городов из CSV

Для импорта полного списка немецких городов используйте команду:

```bash
php artisan import:cities storage/data/german-postcodes.csv
```

> **Примечание**: Убедитесь, что CSV-файл находится в указанной директории и имеет формат: `Ort;Plz;Bundesland`

## Парсинг событий

### Доступные парсеры

Проект поддерживает парсинг событий из нескольких источников:

1. **Traunreut** - `https://veranstaltungen.traunreut.de`
2. **K1 Rosenheim** - Культурный центр
3. **Traunstein** - Региональные события

### Запуск парсинга

#### Парсинг всех источников

```bash
php artisan parse:site --site=all
```

#### Парсинг конкретного источника

```bash
# Traunreut
php artisan parse:site --site=traunreut

# K1 Rosenheim
php artisan parse:site --site=k1

# Naturefreunde
php artisan parse:site --site=naturefreunde
```

### Настройка парсеров

Конфигурация парсеров находится в `config/parse.php`:

```php
return [
    'traunreut' => [
        'url' => 'https://veranstaltungen.traunreut.de/...',
        'site' => 'traunreut.de',
        'region' => 'Bayern',
        'parse' => [
            'event_list_selector' => '...',
            'title_selector' => '...',
            // ...
        ]
    ],
    // ...
];
```

### Автоматизация парсинга

Добавьте в планировщик задач (cron):

```bash
# Linux/Mac
* * * * * cd /path/to/traunreut && php artisan schedule:run >> /dev/null 2>&1

# Windows (Task Scheduler)
# Создайте одну задачу, которая запускается каждую минуту:
# php C:\laragon\www\traunreut\artisan schedule:run >> NUL 2>&1
```

В `routes/console.php` настройте расписание (уже настроено):

```php
use Illuminate\Support\Facades\Schedule;

// Парсинг каждый четверг в 7:30
Schedule::command('parse:site --site=all')
    ->weeklyOn(4, '07:30')
    ->appendOutputTo(storage_path('logs/parse.log'));

// Перевод каждый четверг в 7:50
Schedule::command('translate:words')
    ->weeklyOn(4, '07:50')
    ->appendOutputTo(storage_path('logs/translate.log'));
```

## Управление переводами событий

### Структура хранения заголовков

Заголовки событий хранятся в отдельной таблице `event_titles` с поддержкой немецкого и русского языков:

```
event_titles
├── id
├── title_de (немецкий, оригинал)
├── title_ru (русский, перевод)
├── created_at
└── updated_at
```

### Обновление переводов

#### 1. Автоматический перевод (DeepL API)

Для автоматического перевода необходимо настроить DeepL API:

```env
DEEPL_API_KEY=your_deepl_api_key
```

Запустите команду перевода:

```bash
php artisan translate:words
```

Эта команда:
- Найдет все заголовки без русского перевода
- Отправит их в DeepL API
- Сохранит переводы в `title_ru`
- Учтет лимиты API (отслеживается в таблице `deepl_api_counts`)

#### 2. Ручное обновление переводов

Через Tinker:

```bash
php artisan tinker
```

```php
// Найти заголовок
$title = \App\Models\EventTitle::where('title_de', 'Brauereiführung')->first();

// Обновить перевод
$title->title_ru = 'Экскурсия по пивоварне';
$title->save();

// Массовое обновление
\App\Models\EventTitle::whereNull('title_ru')
    ->limit(10)
    ->get()
    ->each(function($title) {
        $title->title_ru = translateFunction($title->title_de);
        $title->save();
    });
```

#### 3. Импорт переводов из CSV

Создайте CSV-файл с колонками: `title_de,title_ru`

```bash
php artisan import:translations storage/translations.csv
```

### Проверка статуса переводов

```bash
php artisan check:translations
```

Вывод покажет:
- Общее количество заголовков
- Количество переведенных
- Количество без перевода
- Примеры непереведенных заголовков

## Категоризация событий

### Автоматическая категоризация

Система автоматически определяет категории и типы событий на основе ключевых слов.

#### Проверка категоризации

```bash
php artisan check:categories
```

Вывод:
- Общее количество событий
- События без типов
- События с NULL категорией
- Примеры некатегоризированных событий

#### Автоматическое исправление

```bash
php artisan check:categories --fix
```

Эта команда:
- Найдет события без категории
- Проанализирует заголовки и описания
- Присвоит подходящие категории и типы на основе ключевых слов

### Управление типами событий и ключевыми словами

Типы событий и ключевые слова настраиваются в `database/seeders/EventTypeSeeder.php`:

```php
$types = [
    'Sport' => ['Sport', 'Fußball', 'Yoga', 'Workout'],
    'Konzerte & Livemusik' => ['Konzert', 'Musik', 'Live', 'Band'],
    'Gesundheit & Wellness' => ['Massage', 'Entspannung', 'Meditation'],
    // ...
];
```

После изменения запустите:

```bash
php artisan db:seed --class=EventTypeSeeder
```

## Telegram Bot

### Настройка

1. Создайте бота через [@BotFather](https://t.me/botfather)
2. Получите токен и добавьте в `.env`:

```env
TELEGRAM_BOT_TOKEN=123456789:ABCdefGHIjklMNOpqrsTUVwxyz
```

3. Зарегистрируйте бота:

```bash
php artisan telegraph:new-bot
```

4. Установите webhook:

```bash
php artisan telegraph:set-webhook
```

### Использование бота

Пользователи могут:
- Искать события по дате (Сегодня, Завтра, Эта неделя)
- Фильтровать по городу
- Фильтровать по типу события
- Получать детальную информацию о событии

## Тестирование

### Запуск всех тестов

```bash
php artisan test
```

### Запуск конкретных тестов

```bash
# Unit тесты
php artisan test --testsuite=Unit

# Feature тесты
php artisan test --testsuite=Feature

# Конкретный тест
php artisan test --filter=EventTypeDeterminationTest
```

### Структура тестов

```
tests/
├── Unit/
│   ├── BaseParserTest.php          # Тесты базовых функций парсера
│   ├── CityExtractionTest.php      # Тесты извлечения городов
│   ├── EventTypeDeterminationTest.php  # Тесты определения типов
│   └── ImportVerificationTest.php  # Проверка импорта данных
└── Feature/
    ├── BotTest.php                 # Тесты Telegram бота
    └── SoftDeleteDuplicateCheckTest.php  # Тесты дубликатов
```

## Полезные команды

```bash
# Очистка кеша
php artisan cache:clear
php artisan config:clear
php artisan route:clear

# Просмотр маршрутов
php artisan route:list

# Просмотр событий в консоли
php artisan tinker
>>> \App\Models\Event::with('eventTitle')->latest()->take(5)->get()

```

## Структура проекта

```
traunreut/
├── app/
│   ├── Console/Commands/       # Artisan команды
│   ├── Http/Controllers/       # Контроллеры парсеров
│   ├── Models/                 # Eloquent модели
│   └── Telegram/              # Telegram bot handlers
├── config/
│   └── parse.php              # Конфигурация парсеров
├── database/
│   ├── migrations/            # Миграции БД
│   └── seeders/               # Сидеры
├── storage/
│   └── data/                  # CSV файлы для импорта
└── tests/                     # Тесты
```

## Troubleshooting

### Проблема: События не парсятся

1. Проверьте логи: `storage/logs/laravel.log`
2. Проверьте доступность сайта-источника
3. Убедитесь, что селекторы в `config/parse.php` актуальны

### Проблема: Дубликаты событий

```bash
# Удалить дубликаты (осторожно!)
php artisan remove:duplicate
```

### Проблема: Переводы не работают

1. Проверьте API ключ DeepL
2. Проверьте лимиты API: `SELECT * FROM deepl_api_counts`
3. Проверьте логи переводов

## Лицензия

MIT License

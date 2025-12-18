<?php

namespace App\Console\Commands;

use App\Models\DeeplApiCount;
use Carbon\Carbon;
use App\Models\EventTitle;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Throwable;

class TranslateCommand extends Command
{
    const LIMIT_SYMBOLS_PER_MONTH = 480000; // На входе
    const SOURCE_LANGUAGE = 'DE';
    const TARGET_LANGUAGE = 'RU';

    private const BATCH_SIZE = 50; // DeepL рекомендует до 50 текстов за запрос
    private const TIMEOUT = 30;
    private const RETRY_TIMES = 3;
    private const RETRY_DELAY = 1000; // миллисекунды

    protected $signature = 'translate:words';
    protected $description = 'Переводит список предложений через DeepL API с обработкой ошибок и пакетной загрузкой';

    public function handle()
    {
        $emptyTranslations = EventTitle::whereNull('title_ru')->pluck('title_de', 'id')->toArray();
        if (!$emptyTranslations) {
            $this->info('✅ Перевод не требуется!');
            return self::SUCCESS;
        }

        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        $totalCharCount = DeeplApiCount::whereYear('created_at', $currentYear)
            ->whereMonth('created_at', $currentMonth)
            ->sum('char_count');

        if ($totalCharCount > self::LIMIT_SYMBOLS_PER_MONTH) {
            $this->error('❌ Превышен лимит символов DeepL API (в базе): ' . $totalCharCount);
            return self::FAILURE;
        }

        $totalCharacters = 0;
        foreach ($emptyTranslations as $string) {
            $totalCharacters += mb_strlen($string, 'UTF-8');
        }
        $totalCharCount += $totalCharacters;
        if ($totalCharCount > self::LIMIT_SYMBOLS_PER_MONTH) {
            $this->error('❌ Превышен лимит символов DeepL API (при обработке новых слов): ' . $totalCharCount);
            return self::FAILURE;
        }

        $apiKey = config('services.deepl.key', env('DEEPL_API_KEY'));
        $apiUrl = config('services.deepl.url', env('DEEPL_API_URL', 'https://api-free.deepl.com/v2/translate'));

        $this->info('Отправляем ' . count($emptyTranslations) . ' предложений на перевод...');

        try {
            $translationKeys = array_keys($emptyTranslations);
            $translationValues = array_values($emptyTranslations);
            $allTranslations = [];
            foreach (array_chunk($translationValues, self::BATCH_SIZE) as $batch) {
                $formData = [
                    'auth_key' => $apiKey,
                    // 'text' =>  json_encode($batch),
                    'source_lang' => self::SOURCE_LANGUAGE,  // ← исходный язык
                    'target_lang' => self::TARGET_LANGUAGE,  // ← язык перевода
                    'split_sentences' => 0,
                    'preserve_formatting' => 0
                ];

                // Добавляем каждый текст как отдельный параметр text
                $queryParts = [];
                foreach ($formData as $key => $value) {
                    $queryParts[] = urlencode($key) . '=' . urlencode($value);
                }

                foreach ($batch as $text) {
                    $queryParts[] = 'text=' . urlencode($text);
                }

                $queryString = implode('&', $queryParts);
                $response = Http::withHeaders([
                    'Content-Type' => 'application/x-www-form-urlencoded',
                ])
                    ->timeout(self::TIMEOUT)
                    ->retry(self::RETRY_TIMES, self::RETRY_DELAY)
                    ->withBody($queryString, 'application/x-www-form-urlencoded')
                    ->post($apiUrl)
                    ->throw();

                $data = $response->json();
                if (!isset($data['translations'])) {
                    $this->error('⚠️ Ответ не содержит переводов');
                    return self::FAILURE;
                }
                $allTranslations = array_merge($allTranslations, $data['translations']);
            }

            $deeplApiCount = new DeeplApiCount();
            $deeplApiCount->char_count = $totalCharacters;
            $deeplApiCount->save();

            $translations = array_combine($translationKeys, $allTranslations);
            foreach ($translations as $eventTitleId => $translation) {
                EventTitle::where('id', $eventTitleId)->update(['title_ru' => $translation['text']]);
            }

            $this->info('✅ Перевод успешно завершён!');
            return self::SUCCESS;
        } catch (Throwable $e) {
            $this->error('❌ Ошибка при обращении к DeepL API: ' . $e->getMessage());
            return self::FAILURE;
        }
    }
}

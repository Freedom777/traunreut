<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MigrateEventTypesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:event-types';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate existing event_types data to normalized structure';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting event types migration...');

        // Step 1: Extract unique event types from existing events
        $this->info('Extracting unique event types...');
        $events = \App\Models\Event::whereNotNull('event_types')->get();
        $uniqueTypes = collect();

        foreach ($events as $event) {
            $types = json_decode($event->event_types, true);
            if (is_array($types)) {
                $uniqueTypes = $uniqueTypes->merge($types);
            }
        }

        $uniqueTypes = $uniqueTypes->unique()->filter()->values();
        $this->info('Found ' . $uniqueTypes->count() . ' unique event types');

        // Step 2: Create EventType records
        $this->info('Creating EventType records...');
        $eventTypeMap = [];
        foreach ($uniqueTypes as $typeName) {
            $eventType = \App\Models\EventType::firstOrCreate(['name' => $typeName]);
            $eventTypeMap[$typeName] = $eventType->id;
        }

        // Step 3: Create pivot records
        $this->info('Creating pivot records...');
        $bar = $this->output->createProgressBar($events->count());
        $bar->start();

        foreach ($events as $event) {
            $types = json_decode($event->event_types, true);
            if (is_array($types)) {
                $typeIds = collect($types)->map(fn($name) => $eventTypeMap[$name] ?? null)->filter()->toArray();
                $event->eventTypes()->sync($typeIds);
            }
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();

        // Step 4: Seed common keywords
        $this->info('Seeding event type keywords...');
        $keywordData = [
            'Sport' => ['sport', 'yoga', 'fitness', 'radtour', 'segway', 'biathlon', 'bogenschiessen', 'nordic-walking', 'workout', 'pilates', 'turnier'],
            'Kultur' => ['ausstellung', 'museen', 'kunstausstellung', 'museum', 'theater', 'kleinkunst', 'bühne', 'oper'],
            'Familie' => ['kinder', 'familien', 'pony', 'bilderbuch', 'märchen', 'familienprogramm'],
            'Gastronomie' => ['kulinarisches', 'brauerei', 'schnitzel', 'fondue', 'brauereiführung'],
            'Wellness' => ['gesundheit', 'meditation', 'massage', 'feldenkrais', 'entspann'],
            'Workshop' => ['workshop', 'kurs', 'schmuckdesign', 'malkurs'],
            'Natur' => ['naturerlebnisse', 'wanderherbst', 'wanderung', 'naturführung'],
            'Literatur' => ['lesung', 'literatur', 'autoren'],
            'Konzerte' => ['konzert', 'livemusik', 'musik', 'band'],
            'Feste' => ['fest', 'markt', 'märkte'],
        ];

        foreach ($keywordData as $typeName => $keywords) {
            $eventType = \App\Models\EventType::where('name', $typeName)->first();
            if ($eventType) {
                foreach ($keywords as $keyword) {
                    \App\Models\EventTypeKeyword::firstOrCreate([
                        'keyword' => $keyword,
                        'event_type_id' => $eventType->id
                    ]);
                }
            }
        }

        $this->info('Migration completed successfully!');
    }
}

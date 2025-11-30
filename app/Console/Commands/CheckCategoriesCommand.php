<?php

namespace App\Console\Commands;

use App\Models\Event;
use Illuminate\Console\Command;

class CheckCategoriesCommand extends Command
{
    protected $signature = 'check:categories {--fix}';
    protected $description = 'Check and fix event categories/types';

    public function handle()
    {
        $total = Event::count();
        $noTypes = Event::doesntHave('eventTypes')->count();
        $nullCategory = Event::whereNull('category')->count();
        $withCategoryNoTypes = Event::whereNotNull('category')->doesntHave('eventTypes')->count();

        $this->info("Total Events: $total");
        $this->info("Events without EventTypes: $noTypes");
        $this->info("Events with NULL category: $nullCategory");
        $this->info("Events with category but no EventTypes: $withCategoryNoTypes");

        if ($withCategoryNoTypes > 0) {
            $this->info("\nSample categories from events without types:");
            $samples = Event::whereNotNull('category')->doesntHave('eventTypes')->limit(50)->pluck('category')->unique();
            foreach ($samples as $cat) {
                $this->line("- $cat");
            }
        }

        if ($this->option('fix')) {
            $this->info("\nFixing categories...");
            $keywords = \App\Models\EventTypeKeyword::with('eventType')->get();
            
            // Fix events with NULL category
            $eventsToFix = Event::whereNull('category')->with('eventTitle')->get();
            $fixedCount = 0;

            foreach ($eventsToFix as $event) {
                $title = $event->eventTitle ? $event->eventTitle->title_de : '';
                $content = mb_strtolower($title . ' ' . $event->description);
                $matchedTypes = [];

                foreach ($keywords as $keywordModel) {
                    if (str_contains($content, mb_strtolower($keywordModel->keyword))) {
                        $matchedTypes[] = $keywordModel->eventType;
                    }
                }

                if (!empty($matchedTypes)) {
                    // Use the first matched type name as category if still null
                    if (!$event->category) {
                        $event->category = $matchedTypes[0]->name;
                        $event->save();
                    }

                    // Attach types
                    $typeIds = array_map(fn($t) => $t->id, $matchedTypes);
                    $event->eventTypes()->syncWithoutDetaching($typeIds);
                    $fixedCount++;
                    $this->info("Fixed Event ID {$event->id}: Assigned category '{$matchedTypes[0]->name}' and types [" . implode(', ', array_map(fn($t) => $t->name, $matchedTypes)) . "]");
                }
            }
            $this->info("Total events fixed: $fixedCount");
            return;
        }

        if ($nullCategory > 0) {
            $this->info("\nSample titles from events with NULL category:");
            // Access title via relationship
            $events = Event::whereNull('category')->with('eventTitle')->limit(20)->get();
            foreach ($events as $event) {
                 $title = $event->eventTitle ? $event->eventTitle->title_de : 'N/A';
                 $this->line("- ID: {$event->id} | Title: $title");
            }
        }
    }
}

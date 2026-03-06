<?php

return [
    'url' => 'https://www.alpenverein-traunstein.de/gruppen/ortsgruppe-traunreut/veranstaltungen',
    'local_file' => __DIR__ . '/../../TEST_HTML/alpenverein_full.html',
    'site' => 'alpenverein-traunstein.de',
    'city' => 'Traunreut',
    'event_types' => ['Gesundheit & Wellness', 'Naturerlebnisse', 'Sport'],
    'category' => 'Naturerlebnisse',
    'parse' => [
        'start_block_selector' => 'div.content',
        'event_list_selector' => 'div.field--type-text-with-summary',
    ],
];

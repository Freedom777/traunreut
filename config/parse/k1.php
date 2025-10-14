<?php

return [
    'url' => 'https://www.k1-traunreut.de/programm',
    'region' => 'Bayern',
    'site' => 'k1-traunreut.de',
    'city' => 'Traunreut',
    'event_types' => ['Konzerte', 'Theater', 'Kultur', 'Kinderveranstaltungen', 'Comedy', 'Musik'],
    'parse' => [
        'start_block_selector' => '#gefilterte-events',
        'event_list_selector' => 'section',
        'title_selector' => 'data-title',
        'artist_selector' => 'data-artist',
        'date_selector' => 'data-date',
        'category_selector' => 'data-cat',
    ],
];

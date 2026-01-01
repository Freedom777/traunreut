<?php

return [
    'url' => 'https://www.naturfreunde-traunreut.de/',
    'site' => 'naturfreunde-traunreut.de',
    'city' => 'Traunreut',
    'event_types' => ['Gesundheit & Wellness', 'Naturerlebnisse', 'Sport'],
    'parse' => [
        'start_block_selector' => 'div.dmRespCol.small-12.medium-6.large-6 > div.dmNewParagraph',
        'event_list_selector' => 'li.m-size-14.size-18',
        'title_selector' => 'strong.font-size-18:nth-of-type(2)',
        'date_selector' => 'strong.font-size-18:nth-of-type(1)',
    ],
];

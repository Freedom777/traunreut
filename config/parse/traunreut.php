<?php

return [
    'url' => 'https://veranstaltungen.traunreut.de/traunreut/?form=search&searchType=search&dateFrom=' .
        date('Y-m-d') . '&dateTo=' . date('Y-m-d', strtotime('+1 month')) .
        '&timeFrom=0&latitude=47.955&longitude=12.5715&location=Traunreut&distance=50',
    'region' => 'Bayern',
    'site' => 'traunreut.de',
    // 'city' => 'Traunreut',
    // 'event_types' => ['Konzerte', 'Theater', 'Kultur', 'Kinderveranstaltungen', 'Comedy', 'Musik'],
    'parse' => [
        'event_list_selector' => 'article.-IMXEVNT-listElement',
        'title_selector' => '.-IMXEVNT-seoTitle',
        'category_selector' => '.-IMXEVNT-listElement__text__subline',
        'info_selector' => '.-IMXEVNT-listElement__text__info',
        'description_selector' => '.-IMXEVNT-listElement__text__extended p',

        'content_block_selector' => 'div.-IMXEVENT-lazyLoadList__page',
        'item_selector' => 'article.-IMXEVNT-listElement',
    ],
];

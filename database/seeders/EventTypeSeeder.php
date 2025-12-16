<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EventType;
use App\Models\EventTypeKeyword;

class EventTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            'Ausstellungen & Museen' => ['Ausstellung', 'Museum', 'Kunst', 'Galerie', 'Vernissage'],
            'Sport' => ['Sport', 'Fußball', 'Training', 'Wanderung', 'Lauf', 'Turnier', 'Workout', 'Gymnastik', 'Outdoor', 'Tour'],
            'Konzerte & Livemusik' => ['Konzert', 'Musik', 'Live', 'Band', 'Orchester', 'Chor', 'Singen', 'Gesang'],
            'Kinder & Familie' => ['Kinder', 'Familie', 'Jugend', 'Puppentheater', 'Märchen', 'Kinderveranstaltungen'],
            'Feste & Märkte' => ['Fest', 'Markt', 'Flohmarkt', 'Weihnachtsmarkt', 'Jahrmarkt', 'Christkindlmarkt', 'Christbaumverkauf', 'Verkauf', 'Brauchtum'],
            'Kulinarisches' => ['Kulinarisch', 'Essen', 'Trinken', 'Brunch', 'Frühstück', 'Dinner', 'Brauerei', 'Brauereiführung', 'Bauernmarkt', 'Direktvermarktung', 'Gastronomie'],
            'Theater & Bühne' => ['Theater', 'Bühne', 'Oper', 'Kabarett', 'Schauspiel', 'Musical', 'Comedy'],
            'Vorträge & Lesungen' => ['Vortrag', 'Lesung', 'Literatur', 'Buch', 'Bildungsveranstaltungen'],
            'Party & Lifestyle' => ['Party', 'Disco', 'Club', 'Tanz'],
            'Gesundheit & Wellness' => ['Gesundheit', 'Wellness', 'Massage', 'Entspannung', 'Yoga', 'Meditation', 'Heilsam', 'Meditativ'],
            'Religion & Kirche' => ['Kirche', 'Gottesdienst', 'Messe', 'Amt', 'Engelamt', 'Andacht', 'Heilig', 'Kirchliche Veranstaltungen '],
            'Sonstiges' => ['Sonstiges', 'Verschiedenes', 'Ehrung', 'Siegerehrung', 'Schießen', 'Böllerschießen'],
        ];

        foreach ($types as $typeName => $keywords) {
            $eventType = EventType::firstOrCreate(['name' => $typeName]);

            foreach ($keywords as $keyword) {
                EventTypeKeyword::firstOrCreate([
                    'event_type_id' => $eventType->id,
                    'keyword' => $keyword
                ]);
            }
        }
    }
}

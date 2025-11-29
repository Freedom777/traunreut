<?php

namespace App\Console\Commands;

use App\Models\Event;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class RemoveDuplicateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'remove:duplicate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove events duplicate';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $duplicatesToDelete = Event::query()
            ->select('events.*')
            ->join(DB::raw('(
        SELECT start_date, event_title_id, city_id, MAX(id) as keep_id
        FROM events
        GROUP BY start_date, event_title_id, city_id
        HAVING COUNT(*) > 1
    ) as keeper'), function ($join) {
                $join->on('events.start_date', '=', 'keeper.start_date')
                    ->on('events.event_title_id', '=', 'keeper.event_title_id')
                    ->on('events.city_id', '=', 'keeper.city_id');
            })
            ->whereColumn('events.id', '!=', 'keeper.keep_id')
            ->get();

        $this->info('Количество событий на удаление: ' . $duplicatesToDelete->count());

        $duplicatesToDelete->each->delete();
    }
}

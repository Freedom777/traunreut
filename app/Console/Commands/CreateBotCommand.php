<?php

namespace App\Console\Commands;

use DefStudio\Telegraph\Models\TelegraphBot;
use Illuminate\Console\Command;

class CreateBotCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:bot';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create bot';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $bot = TelegraphBot::create([
            'token' => config('services.telegram.bot_token'),
            'name' => 'Traunreut Events Bot',
        ]);
    }
}

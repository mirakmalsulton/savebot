<?php

namespace App\Console\Commands;

use App\Modules\Telegram\Entities\User\TgUser;
use Illuminate\Console\Command;

class Stats extends Command
{
    protected $signature = 'stats';

    protected $description = 'Command description';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $users = TgUser::query()->orderBy('created_at')->get(['name', 'balance', 'created_at']);
        $this->info(json_encode($users, JSON_PRETTY_PRINT));
    }
}

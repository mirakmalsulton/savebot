<?php

namespace App\Console\Commands;

use App\Modules\Telegram\Providers\TelegramModuleProvider;
use Illuminate\Console\Command;

class TgSetup extends Command
{
    protected $signature = 'tg:setup';

    protected $description = 'Command description';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $domain = config(TelegramModuleProvider::MODULE . '.DOMAIN');
        $apiKey = config(TelegramModuleProvider::MODULE . '.TOKEN');
        $url = "https://api.telegram.org/bot{$apiKey}/setWebhook?url={$domain}/api/telegram?XDEBUG_SESSION_START=REMOTE";
        file_get_contents($url);
        $this->info($url);
        return 0;
    }
}

<?php

namespace App\Modules\Telegram\Providers;

use App\Modules\Telegram\Clients\TgClient;
use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;

class TelegramModuleProvider extends ServiceProvider
{
    const string MODULE = 'modules.tg';

    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../configs.php', self::MODULE);

        $this->app->singleton(TgClient::class, function () {
            return new TgClient(
                new Client([
                    'base_uri' => 'https://api.telegram.org/bot' . config(self::MODULE . '.TOKEN') . '/',
                ]),
                new Client([
                    'base_uri' => 'https://api.telegram.org/file/bot' . config(self::MODULE . '.TOKEN') . '/',
                ])
            );
        });
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Migrations');
    }
}

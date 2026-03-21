<?php

namespace App\Modules\Telegram\Routes;

use App\Modules\Telegram\Handlers\MainMenuHandler;
use App\Modules\Telegram\Handlers\UrlHandler;
use Illuminate\Support\Facades\App;

class MessageRoutes
{
    public static function handle(string $message): void
    {
        if (MainMenuHandler::checkByString($message) || $message === '/start') {
            App::make(MainMenuHandler::class)->index();
            return;
        }

        if (UrlHandler::checkByString($message)) {
            App::make(UrlHandler::class)->index($message);
            return;
        }

        App::make(MainMenuHandler::class)->index();
    }
}

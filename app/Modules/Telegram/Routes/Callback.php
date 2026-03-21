<?php

namespace App\Modules\Telegram\Routes;

use App\Modules\Telegram\Entities\Callback\Button;
use App\Modules\Telegram\Handlers\LangHandler;
use Illuminate\Support\Facades\App;

class Callback
{
    public static function handle(int $callbackId, int $messageId, string $key)
    {
        if (!$button = Button::query()->where('key', $key)->first()) {
            return;
        }

        if (LangHandler::checkByString($button->handler)) {
            if ($button->method === 'select') {
                App::call(function (LangHandler $handler) use ($button) {
                    $handler->select($button->getParam('lang'));
                });
                return;
            }
        }
    }
}

<?php

namespace App\Modules\Telegram\Handlers;

use App\Modules\Telegram\Dtos\TextMessage;
use App\Modules\Telegram\Entities\Callback\Button;
use App\Modules\Telegram\Jobs\TgSendJob;
use App\Modules\Telegram\Services\AuthService;

class MainMenuHandler extends BaseHandler
{
    const string COMMAND = 'Main menu';

    public function index(): void
    {
        AuthService::$user->clearState();

        $message = new TextMessage(
            AuthService::$user->id,
            __('main_menu_handler.Please send me a url.'),
            false
        );

//        $message->addColumnButton(0, Button::make(self::command()));
//        $message->addColumnButton(0, Button::make(LangHandler::command()));
//        $message->addRowButton(Button::make(HelpHandler::command()));

        TgSendJob::dispatch($message);

        AuthService::$user->clearState();
    }
}

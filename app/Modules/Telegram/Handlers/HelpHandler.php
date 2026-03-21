<?php

namespace App\Modules\Telegram\Handlers;

use App\Modules\Telegram\Dtos\TextMessage;
use App\Modules\Telegram\Jobs\TgSendJob;
use App\Modules\Telegram\Services\AuthService;

class HelpHandler extends BaseHandler
{
    const string COMMAND = 'Help';

    public function index(): void
    {
        AuthService::$user->clearState();

        $message = new TextMessage(AuthService::$user->id, __('help.examples'), true);
        TgSendJob::dispatch($message);
    }
}

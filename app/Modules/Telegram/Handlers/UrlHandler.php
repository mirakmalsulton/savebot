<?php

namespace App\Modules\Telegram\Handlers;

use App\Modules\Downloader\Jobs\DownloadJob;
use App\Modules\Telegram\Dtos\TextMessage;
use App\Modules\Telegram\Jobs\TgSendJob;
use App\Modules\Telegram\Services\AuthService;
use Illuminate\Support\Facades\Validator;

class UrlHandler extends BaseHandler
{
    public function index(string $url): void
    {
        $this->fastAnswer();
        DownloadJob::dispatch(AuthService::$user, $url);
    }

    private function fastAnswer(string $message = null): void
    {
        $message = $message ?? __('main_menu_handler.Please wait...');
        $message = new TextMessage(AuthService::$user->id, $message, false);
        TgSendJob::dispatch($message);
    }

    public static function checkByString(string $requestCommand, $handlerCommand = null): bool
    {
        return Validator::make(['url' => $requestCommand], ['url' => 'url'])->passes();
    }
}

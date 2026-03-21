<?php

namespace App\Modules\Downloader\Jobs;

use App\Modules\Downloader\Manager;
use App\Modules\Telegram\Dtos\IDto;
use App\Modules\Telegram\Dtos\PhotoMessage;
use App\Modules\Telegram\Dtos\TextMessage;
use App\Modules\Telegram\Entities\User\TgUser;
use App\Modules\Telegram\Jobs\TgSendJob;
use App\Modules\Telegram\Services\AuthService;
use App\Modules\Telegram\Services\Sender;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;
use Throwable;

readonly class DownloadJob implements ShouldQueue
{
    use Dispatchable;

    public function __construct(private TgUser $user, private string $url)
    {
    }

    public function handle(Sender $sender, Manager $downloadManager): void
    {
        $provider = $downloadManager->getProvider($this->url);

        if (!$provider) {
            $message = new TextMessage($this->user->id, 'This platform is not supported', false);
            TgSendJob::dispatch($message);
            return;
        }

        try {
            $file = $provider->download($this->url);
            $message = new TextMessage($this->user->id, $file, false);
        } catch (Throwable $e) {
            $message = new TextMessage($this->user->id, htmlspecialchars($e->getMessage()), false);
        }

        TgSendJob::dispatch($message);
    }
}

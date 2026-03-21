<?php

namespace App\Modules\Telegram\Jobs;

use App\Modules\Telegram\Dtos\IDto;
use App\Modules\Telegram\Dtos\PhotoMessage;
use App\Modules\Telegram\Dtos\TextMessage;
use App\Modules\Telegram\Services\Sender;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;
use Throwable;

readonly class TgSendJob implements ShouldQueue
{
    use Dispatchable;

    public function __construct(private IDto $message)
    {
    }

    public function handle(Sender $sender): void
    {
        try {
            if ($this->message instanceof TextMessage) {
                $sender->sendTextMessage($this->message);
            } elseif ($this->message instanceof PhotoMessage) {
                $sender->sendPhotoMessage($this->message);
            } else {
                Log::error('Unknown message type', ['message' => $this->message]);
            }
        } catch (Throwable $e) {
            Log::error($e->getMessage());
        }
    }
}

<?php

namespace App\Modules\Telegram\Listeners;

use App\Events\CodeExecutedEvent;
use App\Modules\Telegram\Dtos\FileMessage;
use App\Modules\Telegram\Services\Sender;
use Illuminate\Contracts\Queue\ShouldQueue;

readonly class TgSendFileListener implements ShouldQueue
{
    public function __construct(private Sender $sender)
    {
    }

    public function handle(CodeExecutedEvent $event): void
    {
        $message = new FileMessage($event->getUserId(), $event->getResultPath());
        $this->sender->sendFileMessage($message);
    }
}

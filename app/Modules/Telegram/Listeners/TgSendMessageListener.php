<?php

namespace App\Modules\Telegram\Listeners;

use App\Modules\Telegram\Dtos\TextMessage;
use App\Modules\Telegram\Interfaces\IMessageEvent;
use App\Modules\Telegram\Services\Sender;
use Illuminate\Contracts\Queue\ShouldQueue;

readonly class TgSendMessageListener implements ShouldQueue
{
    public function __construct(private Sender $sender)
    {
    }

    public function handle(IMessageEvent $event): void
    {
        $message = new TextMessage($event->getUserId(), $event->getMessage(), false);
        $this->sender->sendTextMessage($message);
    }
}

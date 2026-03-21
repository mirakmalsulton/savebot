<?php

namespace App\Modules\Telegram\Interfaces;

interface IMessageEvent
{
    public function getUserId(): int;

    public function getMessage(): string;
}

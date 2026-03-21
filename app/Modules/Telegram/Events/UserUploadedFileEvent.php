<?php

namespace App\Modules\Telegram\Events;

readonly class UserUploadedFileEvent
{
    public function __construct(private int $userId, private string $message, private string $fileId)
    {
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getFileId(): string
    {
        return $this->fileId;
    }
}

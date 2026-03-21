<?php

namespace App\Modules\Telegram\Services;

use App\Modules\Telegram\Clients\TgClient;
use App\Modules\Telegram\Dtos\IDto;

readonly class Sender
{
    public function __construct(private TgClient $client)
    {
    }

    public function sendTextMessage(IDto $message): void
    {
        $this->client->post('sendmessage', $message->toArray());
    }

    public function sendPhotoMessage(IDto $message): void
    {
        $this->client->post('sendPhoto', $message->toArray());
    }

    public function sendFileMessage(IDto $message): void
    {
        $this->client->post('sendDocument', $message->toArray());
    }

    public function getFileContent(string $fileId): ?string
    {
        $result = $this->client->get('getFile', ['file_id' => $fileId]);
        return $this->client->download($result['file_path']);
    }
}

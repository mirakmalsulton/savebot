<?php

namespace App\Modules\Telegram\Dtos;

use App\Modules\Telegram\Entities\Callback\Button;
use Webmozart\Assert\Assert;

class FileMessage implements IDto
{
    private array $keyboard = [];

    public function __construct(
        private readonly int    $chatId,
        private readonly string $path
    )
    {
        Assert::notEmpty($path);
    }

    public function toArray(): array
    {
        $this->keyboard = array_values($this->keyboard);
        return [
            'multipart' => [
                [
                    'name' => 'chat_id',
                    'contents' => $this->chatId,
                ],
                [
                    'name' => 'document',
                    'contents' => fopen($this->path, 'r'),
                ],
            ]
        ];
    }

    public function addRowButton(Button $button): void
    {
        $this->keyboard[] = [['text' => $button->text, 'callback_data' => $button->key]];
    }

    public function addColumnButton(int $rowPosition, Button $button): void
    {
        $this->keyboard[$rowPosition][] = ['text' => $button->text, 'callback_data' => $button->key];
    }
}

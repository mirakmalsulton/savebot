<?php

namespace App\Modules\Telegram\Dtos;

use App\Modules\Telegram\Entities\Callback\Button;
use Webmozart\Assert\Assert;

class TextMessage implements IDto
{
    private array $keyboard = [];

    public function __construct(
        private readonly int    $chatId,
        private readonly string $text,
        private readonly bool   $is_inline,
    )
    {
        Assert::notEmpty($text);
        Assert::true(mb_strlen($text) <= 4096, 'Message is too long');
    }

    public function toArray(): array
    {
        $this->keyboard = array_values($this->keyboard);
        return [
            'form_params' => [
                'chat_id' => $this->chatId,
                'text' => $this->text,
                'parse_mode' => 'HTML',
                'disable_web_page_preview' => true,
                'reply_markup' => $this->is_inline
                    ? json_encode(['inline_keyboard' => $this->keyboard], true)
                    : json_encode(['keyboard' => $this->keyboard, 'resize_keyboard' => true], true)
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

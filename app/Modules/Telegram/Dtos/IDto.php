<?php

namespace App\Modules\Telegram\Dtos;

use App\Modules\Telegram\Entities\Callback\Button;

interface IDto
{
    public function toArray(): array;

    public function addRowButton(Button $button): void;

    public function addColumnButton(int $rowPosition, Button $button): void;
}

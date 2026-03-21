<?php

namespace App\Modules\Telegram\Repositories;

use App\Modules\Telegram\Entities\Callback\Button;
use Illuminate\Database\UniqueConstraintViolationException;

class ButtonRepository
{
    public function findOneByKey(string $key): ?Button
    {
        return Button::query()->where('key', $key)->first();
    }

    public function save(Button $button): void
    {
        try {
            $button->save();
        } catch (UniqueConstraintViolationException) {
        }
    }
}

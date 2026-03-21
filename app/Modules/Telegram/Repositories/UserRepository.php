<?php

namespace App\Modules\Telegram\Repositories;

use App\Modules\Telegram\Entities\User\TgUser;

class UserRepository
{
    public function findOneById(int $id): ?TgUser
    {
        return TgUser::find($id);
    }

    public function save(TgUser $user): void
    {
        $user->save();
    }
}

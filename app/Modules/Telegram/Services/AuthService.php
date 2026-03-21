<?php

namespace App\Modules\Telegram\Services;

use App\Modules\Telegram\Entities\User\TgUser;

class AuthService
{
    public static ?TgUser $user = null;

    public function __construct(private readonly UserService $userService)
    {
    }

    public function login(array $from): void
    {
        $user = $this->userService->add($from);
        self::$user = $user;
    }
}

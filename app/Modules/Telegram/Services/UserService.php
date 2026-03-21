<?php

namespace App\Modules\Telegram\Services;

use App\Modules\Telegram\Dtos\TextMessage;
use App\Modules\Telegram\Entities\User\TgUser;
use App\Modules\Telegram\Jobs\TgSendJob;
use App\Modules\Telegram\Repositories\UserRepository;
use Illuminate\Support\Facades\App;

readonly class UserService
{
    const int DEFAULT_BALANCE = 1000 * 100;

    public function __construct(private UserRepository $repository)
    {
    }

    public function add(array $from): TgUser
    {
        $isNew = false;

        if (!$user = $this->repository->findOneById($from['id'])) {
            $user = TgUser::make($from['id'])
                ->withLang(App::getLocale())
                ->withBalance(self::DEFAULT_BALANCE);
            $isNew = true;
        }

        $fullName = null;

        if (isset($from['first_name'])) {
            $fullName .= $from['first_name'];
        }

        if (isset($from['last_name'])) {
            $fullName .= ' ' . $from['last_name'];
        }

        if (!empty($fullName)) {
            $user->setName($fullName);
        }

        $this->repository->save($user);

        if ($isNew) {
            $message = new TextMessage(
                config('modules.tg.ADMIN'),
                'New user: ' . $from['id'] . ' ' . $fullName,
                false);
            TgSendJob::dispatch($message);
        }

        return $user;
    }
}

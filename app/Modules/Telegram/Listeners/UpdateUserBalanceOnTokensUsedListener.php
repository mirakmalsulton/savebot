<?php

namespace App\Modules\Telegram\Listeners;

use App\Modules\Gpt\Events\UserUsedTokensEvent;
use App\Modules\Telegram\Repositories\UserRepository;
use Illuminate\Contracts\Queue\ShouldQueue;

readonly class UpdateUserBalanceOnTokensUsedListener implements ShouldQueue
{
    public function __construct(private UserRepository $repository)
    {
    }

    public function handle(UserUsedTokensEvent $event): void
    {
        if (!$user = $this->repository->findOneById($event->getUserId())) {
            return;
        }

        $user->decreaseBalance($event->getTokensAmount());
    }
}

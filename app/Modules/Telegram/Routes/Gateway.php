<?php

namespace App\Modules\Telegram\Routes;

use App\Modules\Telegram\Services\AuthService;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Throwable;

readonly class Gateway
{
    public function __construct(private AuthService $authService)
    {
    }

    public function handle(array $request): void
    {
        try {
            if (isset($request['message']['document'])) {
                $this->authService->login($request['message']['from']);
                App::setLocale(AuthService::$user->lang);

                DocumentRoutes::handle($request['message']['document']);
                return;
            }

            if (isset($request['message'])) {
                $this->authService->login($request['message']['from']);
                App::setLocale(AuthService::$user->lang);

                MessageRoutes::handle($request['message']['text']);
                return;
            }

            if (isset($request['callback_query'])) {
                $this->authService->login($request['callback_query']['from']);
                App::setLocale(AuthService::$user->lang);

                Callback::handle(
                    $request['callback_query']['id'],
                    $request['callback_query']['message']['message_id'],
                    $request['callback_query']['data']
                );
            }

            return;
        } catch (Throwable $e) {
            Log::error($e->getMessage(), ['trace' => $e->getTrace()]);
        }
    }
}

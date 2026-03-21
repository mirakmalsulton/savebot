<?php

use App\Modules\Telegram\Providers\TelegramModuleProvider;
use App\Providers\AppServiceProvider;

return [
    AppServiceProvider::class,
    TelegramModuleProvider::class,
];

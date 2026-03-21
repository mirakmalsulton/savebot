<?php

namespace App\Modules\Telegram\Routes;

use App\Modules\Telegram\Handlers\DocumentHandler;
use Illuminate\Support\Facades\App;

class DocumentRoutes
{
    public static function handle(array $document): void
    {
        App::call(function (DocumentHandler $handler) use ($document) {
            $handler->index($document);
        });
    }
}

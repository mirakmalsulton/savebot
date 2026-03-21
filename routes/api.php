<?php

use App\Modules\Telegram\Routes\Gateway;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

Route::post('/telegram', function (Request $request, Gateway $mainRoute) {
    try{
        $mainRoute->handle($request->all());
    } catch (Throwable $e) {
        Log::error($e->getMessage(), ['trace' => $e->getTrace()]);
    }
});

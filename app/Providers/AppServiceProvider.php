<?php

namespace App\Providers;

use App\Modules\Downloader\Instagram;
use App\Modules\Downloader\Manager;
use App\Modules\Downloader\Shorts;
use App\Modules\Downloader\TikTok;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(Manager::class, function () {
            return new Manager([
                TikTok::class,
                Instagram::class,
                Shorts::class
            ]);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}

<?php

declare(strict_types=1);

namespace App\Providers;

use App\Contracts\Services\Map\MapLogManagerContract;
use App\Contracts\Services\Map\MapWebHandlerContract;
use App\Contracts\Services\Map\ParseMapPlaceInfoContract;
use App\Contracts\Services\Map\ParseMapReviewsContract;
use App\Services\YandexMap\ParseYandexMapPlaceInfo;
use App\Services\YandexMap\ParseYandexMapReviews;
use App\Services\YandexMap\YandexMapLogManager;
use App\Services\YandexMap\YandexMapWebHandler;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(MapLogManagerContract::class, YandexMapLogManager::class);
        $this->app->bind(ParseMapPlaceInfoContract::class, ParseYandexMapPlaceInfo::class);
        $this->app->bind(ParseMapReviewsContract::class, ParseYandexMapReviews::class);
        $this->app->bind(MapWebHandlerContract::class, YandexMapWebHandler::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        JsonResource::withoutWrapping();

        RateLimiter::for('api', callback: function (Request $request) {
            return Limit::perMinute(maxAttempts: 120)->by($request->user()?->id ?: $request->ip());
        });

        RateLimiter::for('places.store', callback: function (Request $request) {
            return Limit::perMinute(maxAttempts: 3)->by($request->user()?->id ?: $request->ip());
        });
    }
}

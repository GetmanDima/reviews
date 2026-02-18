<?php

declare(strict_types=1);

namespace App\Providers;

use App\Contracts\Repositories\PlaceParsingLogRepositoryContract;
use App\Contracts\Repositories\PlaceRepositoryContract;
use App\Contracts\Repositories\ReviewRepositoryContract;
use App\Contracts\Repositories\UserRepositoryContract;
use App\Repositories\PlaceParsingLogRepository;
use App\Repositories\PlaceRepository;
use App\Repositories\ReviewRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(UserRepositoryContract::class, UserRepository::class);
        $this->app->bind(PlaceRepositoryContract::class, PlaceRepository::class);
        $this->app->bind(ReviewRepositoryContract::class, ReviewRepository::class);
        $this->app->bind(PlaceParsingLogRepositoryContract::class, PlaceParsingLogRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}

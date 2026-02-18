<?php

declare(strict_types=1);

namespace App\Contracts\Services\Map;

use App\Models\Place;
use Facebook\WebDriver\Remote\RemoteWebDriver;

interface MapWebHandlerContract
{
    public function getDriver(): RemoteWebDriver;

    public function fetchPage(Place $place): void;

    public function needNextReviews(): bool;

    public function fetchNextReviews(): void;

    public function getCurrentPageFirstReviewNumber(): int;

    public function getCurrentPageLastReviewNumber(): int;
}

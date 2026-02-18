<?php

declare(strict_types=1);

namespace App\Contracts\Services\Map;

use App\Models\Place;
use App\Models\PlaceParsingLog;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Throwable;

interface MapLogManagerContract
{
    public function generatePlaceInfoContentFilePath(Place $place): string;

    public function generateReviewsContentFilePath(Place $place, int $fromReviewNumber, int $toReviewNumber): string;

    public function savePageContentIntoFile(RemoteWebDriver $driver, string $filePath): void;

    public function logException(PlaceParsingLog $log, Throwable $e): void;

    public function getPublicLogFilePath(int $placeId): string;
}

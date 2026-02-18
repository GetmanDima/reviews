<?php

declare(strict_types=1);

namespace App\Contracts\Services\Map;

use App\Models\Place;
use App\Models\PlaceParsingLog;
use Facebook\WebDriver\Remote\RemoteWebDriver;

interface ParseMapPlaceInfoContract
{
    public function handle(RemoteWebDriver $driver, PlaceParsingLog $log): Place;
}

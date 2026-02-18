<?php

declare(strict_types=1);

namespace App\Contracts\Services\Map;

use App\Models\PlaceParsingLog;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Generator;

interface ParseMapReviewsContract
{
    public function handle(RemoteWebDriver $driver, PlaceParsingLog $log): Generator;
}

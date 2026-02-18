<?php

declare(strict_types=1);

namespace App\Exceptions\Map;

use App\Exceptions\BusinessException;

class PublishedAtParsingException extends BusinessException
{
    public function __construct(int $placeParsingLogId)
    {
        parent::__construct("Problem with parsing published at. Place log: $placeParsingLogId");
    }
}

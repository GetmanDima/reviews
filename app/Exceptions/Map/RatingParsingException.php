<?php

declare(strict_types=1);

namespace App\Exceptions\Map;

use App\Exceptions\BusinessException;

class RatingParsingException extends BusinessException
{
    public function __construct(int $placeParsingLogId)
    {
        parent::__construct("Problem with parsing place rating. Place log: $placeParsingLogId");
    }
}

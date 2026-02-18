<?php

declare(strict_types=1);

namespace App\Enums;

enum PlaceParsingLogType: string
{
    case PARSE_PLACE = 'parse_place';
    case PARSE_REVIEWS = 'parse_reviews';
}

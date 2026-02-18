<?php

declare(strict_types=1);

namespace App\Enums;

enum PlaceParsingLogStatus: string
{
    case IN_PROCESS = 'in_process';
    case IN_PROCESS_WITH_ERRORS = 'in_process_with_errors';
    case PROCESSED = 'processed';
    case PROCESSED_WITH_ERRORS = 'processed_with_errors';

    /**
     * @return string[]
     */
    public static function values(): array
    {
        return array_map(
            fn ($status) => $status->value,
            self::cases()
        );
    }
}

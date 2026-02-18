<?php

declare(strict_types=1);

namespace App\Contracts\Repositories;

use App\DataTransferObjects\PlaceParsingLog\CreatePlaceParsingLogDTO;
use App\Enums\PlaceParsingLogStatus;
use App\Models\PlaceParsingLog;

interface PlaceParsingLogRepositoryContract
{
    public function create(CreatePlaceParsingLogDTO $dto): PlaceParsingLog;

    public function updateStatus(int $id, PlaceParsingLogStatus $status): PlaceParsingLog;
}

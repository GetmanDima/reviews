<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Contracts\Repositories\PlaceParsingLogRepositoryContract;
use App\DataTransferObjects\PlaceParsingLog\CreatePlaceParsingLogDTO;
use App\Enums\PlaceParsingLogStatus;
use App\Models\PlaceParsingLog;

class PlaceParsingLogRepository implements PlaceParsingLogRepositoryContract
{
    public function __construct(
        private readonly PlaceParsingLog $placeParsingLog,
    ) {}

    public function create(CreatePlaceParsingLogDTO $dto): PlaceParsingLog
    {
        return $this->placeParsingLog::create($dto->toArray());
    }

    public function updateStatus(int $id, PlaceParsingLogStatus $status): PlaceParsingLog
    {
        $log = $this->placeParsingLog::findOrFail($id);
        $log->update(['status' => $status->value]);

        return $log;
    }
}

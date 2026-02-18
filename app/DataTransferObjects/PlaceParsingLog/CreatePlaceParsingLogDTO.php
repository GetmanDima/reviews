<?php

declare(strict_types=1);

namespace App\DataTransferObjects\PlaceParsingLog;

use App\Contracts\DataTransferObjectContract;
use App\Contracts\Repositories\PlaceRepositoryContract;
use App\Enums\PlaceParsingLogStatus;
use App\Enums\PlaceParsingLogType;
use App\Models\Place;

final readonly class CreatePlaceParsingLogDTO implements DataTransferObjectContract
{
    public function __construct(
        public Place $place,
        public PlaceParsingLogStatus $status,
        public string $filePath,
        public PlaceParsingLogType $type,
        public ?int $fromReview = null,
        public ?int $toReview = null,
    ) {}

    public static function fromArray(array $data): self
    {
        $place = app(PlaceRepositoryContract::class)->findOrFail($data['place_id']);

        return new self(
            place: $place,
            status: PlaceParsingLogStatus::IN_PROCESS,
            filePath: $data['file_path'],
            type: PlaceParsingLogType::from($data['type']),
            fromReview: $data['from_review'] ?? null,
            toReview: $data['to_review'] ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            'place_id' => $this->place->id,
            'status' => $this->status->value,
            'file_path' => $this->filePath,
            'type' => $this->type->value,
            'from_review' => $this->fromReview,
            'to_review' => $this->toReview,
        ];
    }
}

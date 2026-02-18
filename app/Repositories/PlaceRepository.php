<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Contracts\Repositories\PlaceRepositoryContract;
use App\DataTransferObjects\Place\CreatePlaceDTO;
use App\DataTransferObjects\Place\UpdatePlaceDTO;
use App\Enums\PlaceStatus;
use App\Models\Place;
use Illuminate\Support\Collection;

class PlaceRepository implements PlaceRepositoryContract
{
    public function __construct(
        private readonly Place $place,
    ) {}

    public function delete(int $placeId): void
    {
        $this->findOrFail($placeId)->delete();
    }

    public function create(CreatePlaceDTO $dto): Place
    {
        return $this->place::create($dto->toArray());
    }

    public function findOrFail(int $placeId): Place
    {
        return $this->place::findOrFail($placeId);
    }

    public function findLatest(int $userId): ?Place
    {
        return $this->place::where('user_id', $userId)
            ->orderByDesc('id')
            ->first();
    }

    public function paginate(int $userId, int $page, int $perPage): Collection
    {
        return $this->place::where('user_id', $userId)
            ->orderByDesc('id')
            ->limit($perPage)
            ->offset(($page - 1) * $perPage)
            ->get();
    }

    public function update(int $placeId, UpdatePlaceDTO $dto): Place
    {
        $place = $this->findOrFail($placeId);
        $place->update($dto->toArray());

        return $place;
    }

    public function updateStatus(int $placeId, PlaceStatus $status): Place
    {
        $place = $this->findOrFail($placeId);
        $place->update(['status' => $status->value]);

        return $place;
    }

    public function existsByUserAndMapId(int $userId, string $mapId): bool
    {
        return $this->place::where('user_id', $userId)
            ->where('map_id', $mapId)
            ->exists();
    }
}

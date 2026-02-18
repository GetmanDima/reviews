<?php

declare(strict_types=1);

namespace App\Contracts\Repositories;

use App\DataTransferObjects\Place\CreatePlaceDTO;
use App\DataTransferObjects\Place\UpdatePlaceDTO;
use App\Enums\PlaceStatus;
use App\Models\Place;
use Illuminate\Support\Collection;

interface PlaceRepositoryContract
{
    public function delete(int $placeId): void;

    public function create(CreatePlaceDTO $dto): Place;

    public function findOrFail(int $placeId): Place;

    public function findLatest(int $userId): ?Place;

    /**
     * @return Collection<int, Place>
     */
    public function paginate(int $userId, int $page, int $perPage): Collection;

    public function update(int $placeId, UpdatePlaceDTO $dto): Place;

    public function updateStatus(int $placeId, PlaceStatus $status): Place;

    public function existsByUserAndMapId(int $userId, string $mapId): bool;
}

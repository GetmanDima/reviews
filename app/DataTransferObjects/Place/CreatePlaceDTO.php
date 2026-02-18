<?php

declare(strict_types=1);

namespace App\DataTransferObjects\Place;

use App\Contracts\DataTransferObjectContract;
use App\Contracts\Repositories\UserRepositoryContract;
use App\Enums\PlaceStatus;
use App\Models\User;
use App\ValueObjects\Place\PlaceUrl;

final readonly class CreatePlaceDTO implements DataTransferObjectContract
{
    public function __construct(
        public User $user,
        public PlaceStatus $status,
        public string $mapId,
        public PlaceUrl $url,
    ) {}

    public static function fromArray(array $data): self
    {
        $userRepository = app(UserRepositoryContract::class);
        $user = $userRepository->findOrFail($data['user_id']);

        $placeUrl = new PlaceUrl($data['url']);
        $mapId = $placeUrl->getMapId();

        return new self(
            user: $user,
            status: PlaceStatus::CREATED,
            mapId: $mapId,
            url: $placeUrl,
        );
    }

    public function toArray(): array
    {
        return [
            'user_id' => $this->user->id,
            'status' => $this->status->value,
            'map_id' => $this->mapId,
            'url' => $this->url->value,
        ];
    }
}

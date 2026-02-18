<?php

declare(strict_types=1);

namespace App\DataTransferObjects\Review;

use App\Contracts\DataTransferObjectContract;
use App\Contracts\Repositories\PlaceRepositoryContract;
use App\Models\Place;
use Illuminate\Support\Carbon;

final readonly class CreateReviewDTO implements DataTransferObjectContract
{
    public function __construct(
        public Place $place,
        public ?string $image = null,
        public ?string $name = null,
        public ?string $rank = null,
        public ?float $rating = null,
        public ?string $text = null,
        public ?Carbon $publishedAt = null,
    ) {}

    public static function fromArray(array $data): self
    {
        $placeRepository = app(PlaceRepositoryContract::class);
        $place = $placeRepository->findOrFail($data['place_id']);

        return new self(
            place: $place,
            image: $data['image'] ?? null,
            name: $data['name'] ?? null,
            rank: $data['rank'] ?? null,
            rating: $data['rating'] ?? null,
            text: $data['text'] ?? null,
            publishedAt: new Carbon($data['published_at']),
        );
    }

    public function toArray(): array
    {
        return [
            'place_id' => $this->place->id,
            'image' => $this->image,
            'name' => $this->name,
            'rank' => $this->rank,
            'rating' => $this->rating,
            'text' => $this->text,
            'published_at' => $this->publishedAt?->toDateTimeString(),
        ];
    }
}

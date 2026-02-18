<?php

declare(strict_types=1);

namespace App\DataTransferObjects\Place;

use App\Contracts\DataTransferObjectContract;

final readonly class UpdatePlaceDTO implements DataTransferObjectContract
{
    public function __construct(
        public ?string $name = null,
        public ?float $rating = null,
        public ?int $reviewsCount = null,
        public ?int $parsedReviewsCount = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'] ?? null,
            rating: $data['rating'] ?? null,
            reviewsCount: $data['reviews_count'] ?? null,
            parsedReviewsCount: $data['parsed_reviews_count'] ?? null,
        );
    }

    public function toArray(): array
    {
        return array_filter([
            'name' => $this->name,
            'rating' => $this->rating,
            'reviews_count' => $this->reviewsCount,
            'parsed_reviews_count' => $this->parsedReviewsCount,
        ], fn ($value) => $value !== null);
    }
}

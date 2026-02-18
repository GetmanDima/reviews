<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Contracts\Repositories\ReviewRepositoryContract;
use App\DataTransferObjects\Review\CreateReviewDTO;
use App\Models\Review;
use Illuminate\Support\Collection;

class ReviewRepository implements ReviewRepositoryContract
{
    public function __construct(
        private readonly Review $review,
    ) {}

    /**
     * @return Collection<int, Review>
     */
    public function paginate(int $placeId, int $page, int $perPage): Collection
    {
        return $this->review::where('place_id', $placeId)
            ->orderByDesc('published_at')
            ->limit($perPage)
            ->offset(($page - 1) * $perPage)
            ->get();
    }

    public function create(CreateReviewDTO $dto): Review
    {
        return $this->review::create($dto->toArray());
    }
}

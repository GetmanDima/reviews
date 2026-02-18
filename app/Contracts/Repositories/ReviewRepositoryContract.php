<?php

declare(strict_types=1);

namespace App\Contracts\Repositories;

use App\DataTransferObjects\Review\CreateReviewDTO;
use App\Models\Review;
use Illuminate\Support\Collection;

interface ReviewRepositoryContract
{
    /**
     * @return Collection<int, Review>
     */
    public function paginate(int $placeId, int $page, int $perPage): Collection;

    public function create(CreateReviewDTO $dto): Review;
}

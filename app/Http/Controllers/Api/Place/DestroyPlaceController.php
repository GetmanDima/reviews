<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Place;

use App\Contracts\Repositories\PlaceRepositoryContract;
use App\Http\Controllers\Controller;
use App\Models\Place;

class DestroyPlaceController extends Controller
{
    public function __construct(
        private readonly PlaceRepositoryContract $placeRepository,
    ) {}

    public function __invoke(Place $place): void
    {
        $this->placeRepository->delete($place->id);
    }
}

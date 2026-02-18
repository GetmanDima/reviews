<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Place;

use App\Contracts\Repositories\PlaceRepositoryContract;
use App\Http\Controllers\Controller;
use App\Http\Resources\Place\PlaceResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class IndexPlaceController extends Controller
{
    public function __construct(
        private readonly PlaceRepositoryContract $placeRepository,
    ) {}

    public function __invoke(): AnonymousResourceCollection
    {
        /**
         * @var int
         */
        $userId = auth()->user()?->id;

        $places = $this->placeRepository->paginate($userId, 1, 100);

        return PlaceResource::collection($places);
    }
}

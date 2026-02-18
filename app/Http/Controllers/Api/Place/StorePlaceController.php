<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Place;

use App\Contracts\Repositories\PlaceRepositoryContract;
use App\Http\Controllers\Controller;
use App\Http\Requests\Place\StorePlaceRequest;
use App\Http\Resources\Place\PlaceResource;
use App\Jobs\Map\ParseMapPlace;
use Illuminate\Http\JsonResponse;

class StorePlaceController extends Controller
{
    public function __construct(
        private readonly PlaceRepositoryContract $placeRepository,
    ) {}

    public function __invoke(StorePlaceRequest $request): JsonResponse
    {
        $dto = $request->getDTO();

        $place = $this->placeRepository->create($dto);

        ParseMapPlace::dispatch($place);

        return (new PlaceResource($place))->response()->setStatusCode(201);
    }
}

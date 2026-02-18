<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Place;

use App\Http\Controllers\Controller;
use App\Http\Resources\Place\PlaceResource;
use App\Models\Place;
use Illuminate\Http\Resources\Json\JsonResource;

class ShowPlaceController extends Controller
{
    public function __invoke(Place $place): JsonResource
    {
        return new PlaceResource($place);
    }
}

<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web\Place;

use App\Models\Place;
use Inertia\Inertia;
use Inertia\Response;

class ShowPlacePageController
{
    public function __invoke(Place $place): Response
    {
        return Inertia::render('place/Show/PlacePage');
    }
}

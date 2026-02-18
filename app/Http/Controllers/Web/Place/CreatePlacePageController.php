<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web\Place;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;

class CreatePlacePageController extends Controller
{
    public function __invoke(): Response
    {
        return Inertia::render('place/Create/CreatePlacePage');
    }
}

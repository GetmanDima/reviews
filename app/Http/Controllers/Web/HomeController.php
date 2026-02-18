<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Contracts\Repositories\PlaceRepositoryContract;
use Illuminate\Http\RedirectResponse;

class HomeController
{
    public function __construct(
        private readonly PlaceRepositoryContract $placeRepository
    ) {}

    public function __invoke(): RedirectResponse
    {
        /**
         * @var int
         */
        $userId = auth()->user()?->id;

        $place = $this->placeRepository->findLatest($userId);

        if (!$place) {
            return redirect()->to('/places/create');
        }

        return redirect()->to('/places/'.$place->id);
    }
}

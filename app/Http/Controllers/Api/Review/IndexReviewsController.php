<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Review;

use App\Contracts\Repositories\ReviewRepositoryContract;
use App\Http\Controllers\Controller;
use App\Http\Requests\Review\IndexReviewsRequest;
use App\Http\Resources\Review\ReviewResource;
use App\Models\Place;
use Illuminate\Http\JsonResponse;

class IndexReviewsController extends Controller
{
    public function __construct(
        private readonly ReviewRepositoryContract $reviewRepository,
    ) {}

    public function __invoke(Place $place, IndexReviewsRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $page = $validated['page'];
        $perPage = $validated['per_page'];

        $reviews = $this->reviewRepository->paginate($place->id, $page, $perPage);

        return response()->json([
            'data' => ReviewResource::collection($reviews),
            'page' => $page,
            'last_page' => ceil($place->parsed_reviews_count / $perPage),
            'total' => $place->parsed_reviews_count,
        ]);
    }
}

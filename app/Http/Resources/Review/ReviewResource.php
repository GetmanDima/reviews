<?php

declare(strict_types=1);

namespace App\Http\Resources\Review;

use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Review */
class ReviewResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'image' => $this->image,
            'name' => $this->name,
            'rank' => $this->rank,
            'rating' => $this->rating,
            'text' => $this->text,
            'published_at' => $this->published_at?->toDateTimeString(),
        ];
    }
}

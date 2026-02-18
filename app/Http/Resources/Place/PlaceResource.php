<?php

declare(strict_types=1);

namespace App\Http\Resources\Place;

use App\Models\Place;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Place */
class PlaceResource extends JsonResource
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
            'status' => $this->status->value,
            'map_id' => $this->map_id,
            'url' => $this->url,
            'name' => $this->name,
            'rating' => $this->rating,
            'reviews_count' => $this->reviews_count,
            'parsed_reviews_count' => $this->parsed_reviews_count,
        ];
    }
}

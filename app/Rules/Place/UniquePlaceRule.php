<?php

declare(strict_types=1);

namespace App\Rules\Place;

use App\Contracts\Repositories\PlaceRepositoryContract;
use App\ValueObjects\Place\PlaceUrl;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class UniquePlaceRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!$value || !is_string($value)) {
            return;
        }

        $placeUrl = new PlaceUrl($value);
        $mapId = $placeUrl->getMapId();

        /**
         * @var int
         */
        $userId = auth()->user()?->id;

        if (app(PlaceRepositoryContract::class)->existsByUserAndMapId($userId, $mapId)) {
            $fail(__('place.validation.unique_place'));
        }
    }
}

<?php

declare(strict_types=1);

namespace App\Http\Requests\Place;

use App\DataTransferObjects\Place\CreatePlaceDTO;
use App\Rules\Place\UniquePlaceRule;
use App\ValueObjects\Place\PlaceUrl;
use Illuminate\Foundation\Http\FormRequest;

class StorePlaceRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'url' => [
                'required',
                'string',
                'regex:/^'.PlaceUrl::PATTERN.'$/',
                new UniquePlaceRule,
            ],
        ];
    }

    public function getDTO(): CreatePlaceDTO
    {
        return CreatePlaceDTO::fromArray([
            'user_id' => auth()->id(),
            'url' => $this->validated('url'),
        ]);
    }
}

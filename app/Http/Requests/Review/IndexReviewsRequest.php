<?php

declare(strict_types=1);

namespace App\Http\Requests\Review;

use Illuminate\Foundation\Http\FormRequest;

class IndexReviewsRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'page' => 'required|integer|min:1',
            'per_page' => 'required|integer|min:1|max:100',
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('page')) {
            $this->merge([
                'page' => (int) $this->page,
            ]);
        }

        if ($this->has('per_page')) {
            $this->merge([
                'per_page' => (int) $this->per_page,
            ]);
        }
    }
}

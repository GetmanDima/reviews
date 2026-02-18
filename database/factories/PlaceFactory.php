<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\PlaceStatus;
use App\Models\Place;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Place>
 */
class PlaceFactory extends Factory
{
    protected $model = Place::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $status = fake()->randomElement(PlaceStatus::values());

        return [
            'user_id' => User::factory(),
            'status' => $status,
            'map_id' => fake()->uuid(),
            'url' => fake()->url(),
            'name' => $status === PlaceStatus::CREATED->value ? null : fake()->word(),
            'rating' => $status === PlaceStatus::CREATED->value ? null : fake()->randomFloat(2, 0, 10),
            'reviews_count' => 10,
            'parsed_reviews_count' => 10,
        ];
    }
}

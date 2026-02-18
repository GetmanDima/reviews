<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Place;
use App\Models\Review;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Review>
 */
class ReviewFactory extends Factory
{
    protected $model = Review::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'place_id' => Place::factory(),
            'image' => fake()->optional()->imageUrl(),
            'name' => fake()->optional()->name(),
            'rank' => fake()->optional()->word(),
            'rating' => fake()->optional()->randomFloat(2, 0, 10),
            'text' => fake()->optional()->paragraph(),
            'published_at' => fake()->dateTimeBetween('-2 years', 'now'),
        ];
    }
}

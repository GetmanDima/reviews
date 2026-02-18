<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Place;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'first_name' => 'Ivan',
            'last_name' => 'Ivanov',
            'middle_name' => 'Ivanovich',
        ]);

        $places = Place::factory()->count(10)->create([
            'user_id' => $user->id,
        ]);

        foreach ($places as $place) {
            Review::factory()->count(10)->create([
                'place_id' => $place->id,
            ]);
        }
    }
}

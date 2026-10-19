<?php

namespace Database\Factories;

use App\Models\EventCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\EventCategory>
 */
class EventCategoryFactory extends Factory
{
    protected $model = EventCategory::class;

    public function definition(): array
    {
        return [
            'name' => fake()->unique()->words(2, true).' '.fake()->randomElement(['Meetup', 'Series', 'Program']),
            'description' => fake()->sentence(12),
        ];
    }
}

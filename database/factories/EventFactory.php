<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\EventCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Event>
 */
class EventFactory extends Factory
{
    protected $model = Event::class;

    public function definition(): array
    {
        $date = fake()->dateTimeBetween('+10 days', '+60 days');

        return [
            'category_id' => EventCategory::factory(),
            'title' => fake()->unique()->sentence(3),
            'description' => fake()->paragraphs(3, true),
            'event_date' => $date->format('Y-m-d'),
            'start_time' => fake()->randomElement(['09:00:00', '10:00:00', '11:30:00', '14:00:00', '16:00:00']),
            'end_time' => fake()->randomElement(['12:00:00', '13:00:00', '15:30:00', '17:00:00', '19:00:00']),
            'venue' => fake()->randomElement([
                'Main Auditorium',
                'Sports Complex',
                'Seminar Hall A',
                'Innovation Lab',
                'Open Air Theatre',
                'Conference Room B',
            ]),
            'image' => null,
            'max_participants' => fake()->randomElement([25, 50, 75, 100, 150]),
            'registration_deadline' => now()->addDays(fake()->numberBetween(3, 20))->format('Y-m-d H:i:s'),
            'status' => Event::STATUS_UPCOMING,
        ];
    }

    public function completed(): static
    {
        return $this->state(fn () => [
            'event_date' => fake()->dateTimeBetween('-30 days', '-5 days')->format('Y-m-d'),
            'registration_deadline' => fake()->dateTimeBetween('-40 days', '-10 days')->format('Y-m-d H:i:s'),
            'status' => Event::STATUS_COMPLETED,
        ]);
    }

    public function ongoing(): static
    {
        return $this->state(fn () => [
            'event_date' => now()->format('Y-m-d'),
            'registration_deadline' => now()->subDay()->format('Y-m-d H:i:s'),
            'status' => Event::STATUS_ONGOING,
        ]);
    }
}

<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\EventRegistration>
 */
class EventRegistrationFactory extends Factory
{
    protected $model = EventRegistration::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'event_id' => Event::factory(),
            'status' => fake()->randomElement([
                EventRegistration::STATUS_PENDING,
                EventRegistration::STATUS_APPROVED,
                EventRegistration::STATUS_APPROVED,
                EventRegistration::STATUS_REJECTED,
            ]),
            'registered_at' => fake()->dateTimeBetween('-10 days', 'now'),
        ];
    }

    public function pending(): static
    {
        return $this->state(fn () => ['status' => EventRegistration::STATUS_PENDING]);
    }

    public function approved(): static
    {
        return $this->state(fn () => ['status' => EventRegistration::STATUS_APPROVED]);
    }

    public function rejected(): static
    {
        return $this->state(fn () => ['status' => EventRegistration::STATUS_REJECTED]);
    }
}

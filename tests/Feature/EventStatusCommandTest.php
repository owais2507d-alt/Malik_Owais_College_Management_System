<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\EventCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventStatusCommandTest extends TestCase
{
    use RefreshDatabase;

    private function event(array $overrides = []): Event
    {
        return Event::factory()->create(array_merge([
            'category_id' => EventCategory::factory(),
            'status' => Event::STATUS_UPCOMING,
        ], $overrides));
    }

    public function test_future_event_stays_upcoming(): void
    {
        $this->event(['event_date' => now()->addDays(14)->format('Y-m-d')]);

        $this->artisan('events:update-status')->assertSuccessful();

        $this->assertSame(
            Event::STATUS_UPCOMING,
            Event::query()->orderByDesc('id')->first()->status,
        );
    }

    public function test_past_event_becomes_completed(): void
    {
        $this->event(['event_date' => now()->subDays(3)->format('Y-m-d')]);

        $this->artisan('events:update-status')->assertSuccessful();

        $this->assertSame(
            Event::STATUS_COMPLETED,
            Event::query()->orderByDesc('id')->first()->status,
        );
    }

    public function test_event_in_progress_today_becomes_ongoing(): void
    {
        $this->event([
            'event_date' => now()->format('Y-m-d'),
            'start_time' => '00:00:00',
            'end_time' => '23:59:59',
        ]);

        $this->artisan('events:update-status')->assertSuccessful();

        $this->assertSame(
            Event::STATUS_ONGOING,
            Event::query()->orderByDesc('id')->first()->status,
        );
    }

    public function test_event_not_started_yet_stays_upcoming(): void
    {
        $this->event([
            'event_date' => now()->format('Y-m-d'),
            'start_time' => '23:59:59',
            'end_time' => '23:59:59',
        ]);

        $this->artisan('events:update-status')->assertSuccessful();

        $this->assertSame(
            Event::STATUS_UPCOMING,
            Event::query()->orderByDesc('id')->first()->status,
        );
    }

    public function test_ongoing_event_with_future_date_is_reset_to_upcoming(): void
    {
        $this->event([
            'event_date' => now()->addDays(7)->format('Y-m-d'),
            'status' => Event::STATUS_ONGOING,
        ]);

        $this->artisan('events:update-status')->assertSuccessful();

        $this->assertSame(
            Event::STATUS_UPCOMING,
            Event::query()->orderByDesc('id')->first()->status,
        );
    }
}

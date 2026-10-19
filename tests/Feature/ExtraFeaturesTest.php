<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\EventCategory;
use App\Models\EventRegistration;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExtraFeaturesTest extends TestCase
{
    use RefreshDatabase;

    public function test_event_calendar_returns_a_valid_ics_file(): void
    {
        $event = Event::factory()->create([
            'category_id' => EventCategory::factory(),
            'title' => 'ICS Test Meetup',
            'event_date' => '2026-12-01',
            'start_time' => '10:00:00',
            'end_time' => '13:00:00',
            'venue' => 'Seminar Hall A',
        ]);

        $response = $this->get(route('events.calendar', $event));

        $response->assertOk();
        $response->assertHeader('Content-Type', 'text/calendar; charset=utf-8');
        $response->assertSee('BEGIN:VCALENDAR');
        $response->assertSee('BEGIN:VEVENT');
        $response->assertSee('SUMMARY:ICS Test Meetup');
        $response->assertSee('LOCATION:Seminar Hall A');
        $response->assertSee('DTSTART:20261201T10:00:00');
        $response->assertSee('DTEND:20261201T13:00:00');
    }

    public function test_admin_can_export_participants_as_csv(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $event = Event::factory()->create(['category_id' => EventCategory::factory()]);
        $student = User::factory()->create(['role' => User::ROLE_STUDENT, 'name' => 'CSV Student']);

        EventRegistration::create([
            'user_id' => $student->id,
            'event_id' => $event->id,
            'status' => EventRegistration::STATUS_APPROVED,
            'registered_at' => now(),
        ]);

        $response = $this->actingAs($admin)->get(route('admin.events.participants.export', $event));

        $response->assertOk();
        $response->assertHeader('Content-Type', 'text/csv; charset=utf-8');

        $csv = $response->streamedContent();
        $this->assertStringContainsString('Name,Email,Phone,Status', $csv);
        $this->assertStringContainsString('CSV Student', $csv);
    }

    public function test_student_cannot_export_participants(): void
    {
        $student = User::factory()->create(['role' => User::ROLE_STUDENT]);
        $event = Event::factory()->create(['category_id' => EventCategory::factory()]);

        $this->actingAs($student)
            ->get(route('admin.events.participants.export', $event))
            ->assertStatus(403);
    }

    public function test_contact_page_and_home_render_with_dark_mode_support(): void
    {
        $this->get(route('contact.show'))->assertOk()->assertSee('Toggle dark mode');
        $this->get(route('home'))->assertOk()->assertSee('images/hero-fiesta.jpg');
    }
}

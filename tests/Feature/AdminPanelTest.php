<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\EventCategory;
use App\Models\EventRegistration;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPanelTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create(['role' => User::ROLE_ADMIN]);
    }

    private function student(): User
    {
        return User::factory()->create(['role' => User::ROLE_STUDENT]);
    }

    private function validEventData(array $overrides = []): array
    {
        return array_merge([
            'category_id' => EventCategory::factory()->create()->id,
            'title' => 'Admin Created Seminar',
            'description' => 'A seminar created inside the admin panel test.',
            'event_date' => now()->addDays(10)->format('Y-m-d'),
            'registration_deadline' => now()->addDays(5)->format('Y-m-d'),
            'start_time' => '10:00',
            'end_time' => '13:00',
            'venue' => 'Main Auditorium',
            'max_participants' => 60,
            'status' => Event::STATUS_UPCOMING,
        ], $overrides);
    }

    public function test_admin_dashboard_loads(): void
    {
        $this->actingAs($this->admin())
            ->get(route('admin.dashboard'))
            ->assertOk();
    }

    public function test_admin_can_create_an_event(): void
    {
        $response = $this->actingAs($this->admin())
            ->post(route('admin.events.store'), $this->validEventData());

        $response->assertRedirect(route('admin.events.index'));
        $response->assertSessionHas('status');

        $this->assertDatabaseHas('events', [
            'title' => 'Admin Created Seminar',
            'slug' => 'admin-created-seminar',
            'status' => Event::STATUS_UPCOMING,
        ]);
    }

    public function test_event_creation_requires_valid_fields(): void
    {
        $response = $this->actingAs($this->admin())
            ->from(route('admin.events.create'))
            ->post(route('admin.events.store'), $this->validEventData([
                'title' => '',
                'event_date' => now()->subDay()->format('Y-m-d'),
                'registration_deadline' => now()->addDays(20)->format('Y-m-d'),
                'end_time' => '09:00',
                'max_participants' => 0,
            ]));

        $response->assertRedirect(route('admin.events.create'));
        $response->assertSessionHasErrors([
            'title',
            'event_date',
            'registration_deadline',
            'end_time',
            'max_participants',
        ]);
    }

    public function test_admin_can_update_an_event(): void
    {
        $event = Event::factory()->create();

        $response = $this->actingAs($this->admin())
            ->put(route('admin.events.update', $event), $this->validEventData([
                'category_id' => $event->category_id,
                'title' => 'Updated Event Title',
            ]));

        $response->assertRedirect(route('admin.events.index'));
        $this->assertDatabaseHas('events', [
            'id' => $event->id,
            'title' => 'Updated Event Title',
        ]);
    }

    public function test_admin_can_delete_an_event_softly(): void
    {
        $event = Event::factory()->create();

        $response = $this->actingAs($this->admin())
            ->delete(route('admin.events.destroy', $event));

        $response->assertSessionHas('status');
        $this->assertSoftDeleted('events', ['id' => $event->id]);
    }

    public function test_admin_can_approve_a_pending_registration(): void
    {
        $registration = EventRegistration::create([
            'user_id' => $this->student()->id,
            'event_id' => Event::factory()->create()->id,
            'status' => EventRegistration::STATUS_PENDING,
            'registered_at' => now(),
        ]);

        $response = $this->actingAs($this->admin())
            ->post(route('admin.registrations.approve', $registration));

        $response->assertSessionHas('status');
        $this->assertDatabaseHas('event_registrations', [
            'id' => $registration->id,
            'status' => EventRegistration::STATUS_APPROVED,
        ]);
    }

    public function test_admin_can_reject_a_pending_registration(): void
    {
        $registration = EventRegistration::create([
            'user_id' => $this->student()->id,
            'event_id' => Event::factory()->create()->id,
            'status' => EventRegistration::STATUS_PENDING,
            'registered_at' => now(),
        ]);

        $response = $this->actingAs($this->admin())
            ->post(route('admin.registrations.reject', $registration));

        $response->assertSessionHas('status');
        $this->assertDatabaseHas('event_registrations', [
            'id' => $registration->id,
            'status' => EventRegistration::STATUS_REJECTED,
        ]);
    }

    public function test_category_with_active_events_cannot_be_deleted(): void
    {
        $category = EventCategory::factory()->create();
        Event::factory()->create(['category_id' => $category->id]);

        $response = $this->actingAs($this->admin())
            ->delete(route('admin.categories.destroy', $category));

        $response->assertSessionHas('error');
        $this->assertDatabaseHas('event_categories', ['id' => $category->id]);
    }

    public function test_empty_category_can_be_deleted(): void
    {
        $category = EventCategory::factory()->create();

        $response = $this->actingAs($this->admin())
            ->delete(route('admin.categories.destroy', $category));

        $response->assertSessionHas('status');
        $this->assertSoftDeleted('event_categories', ['id' => $category->id]);
    }

    public function test_participants_page_lists_event_registrations(): void
    {
        $event = Event::factory()->create();

        EventRegistration::create([
            'user_id' => $this->student()->id,
            'event_id' => $event->id,
            'status' => EventRegistration::STATUS_PENDING,
            'registered_at' => now(),
        ]);

        $this->actingAs($this->admin())
            ->get(route('admin.events.participants', $event))
            ->assertOk()
            ->assertSee($event->title);
    }
}

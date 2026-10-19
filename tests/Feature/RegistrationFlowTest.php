<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\EventCategory;
use App\Models\EventRegistration;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationFlowTest extends TestCase
{
    use RefreshDatabase;

    private function student(): User
    {
        return User::factory()->create(['role' => User::ROLE_STUDENT]);
    }

    private function openEvent(array $overrides = []): Event
    {
        return Event::factory()->create(array_merge([
            'category_id' => EventCategory::factory(),
            'max_participants' => 10,
            'registration_deadline' => now()->addDays(5),
            'status' => Event::STATUS_UPCOMING,
        ], $overrides));
    }

    public function test_student_can_register_for_an_open_event(): void
    {
        $student = $this->student();
        $event = $this->openEvent();

        $response = $this->actingAs($student)
            ->post(route('student.registrations.store', $event));

        $response->assertRedirect();
        $response->assertSessionHas('status');

        $this->assertDatabaseHas('event_registrations', [
            'user_id' => $student->id,
            'event_id' => $event->id,
            'status' => EventRegistration::STATUS_PENDING,
        ]);
    }

    public function test_duplicate_registration_is_blocked(): void
    {
        $student = $this->student();
        $event = $this->openEvent();

        $this->actingAs($student)->post(route('student.registrations.store', $event));

        $response = $this->actingAs($student)
            ->post(route('student.registrations.store', $event));

        $response->assertSessionHas('error');
        $this->assertSame(1, EventRegistration::where('user_id', $student->id)->where('event_id', $event->id)->count());
    }

    public function test_registration_is_blocked_when_event_is_full(): void
    {
        $student = $this->student();
        $event = $this->openEvent(['max_participants' => 1]);

        EventRegistration::create([
            'user_id' => $this->student()->id,
            'event_id' => $event->id,
            'status' => EventRegistration::STATUS_APPROVED,
            'registered_at' => now(),
        ]);

        $response = $this->actingAs($student)
            ->post(route('student.registrations.store', $event));

        $response->assertSessionHas('error');
        $this->assertDatabaseMissing('event_registrations', [
            'user_id' => $student->id,
            'event_id' => $event->id,
        ]);
    }

    public function test_registration_is_blocked_after_deadline(): void
    {
        $student = $this->student();
        $event = $this->openEvent(['registration_deadline' => now()->subDay()]);

        $response = $this->actingAs($student)
            ->post(route('student.registrations.store', $event));

        $response->assertSessionHas('error');
        $this->assertDatabaseMissing('event_registrations', [
            'user_id' => $student->id,
            'event_id' => $event->id,
        ]);
    }

    public function test_registration_is_blocked_when_event_is_not_upcoming(): void
    {
        $student = $this->student();
        $event = $this->openEvent(['status' => Event::STATUS_ONGOING]);

        $response = $this->actingAs($student)
            ->post(route('student.registrations.store', $event));

        $response->assertSessionHas('error');
        $this->assertDatabaseMissing('event_registrations', [
            'user_id' => $student->id,
            'event_id' => $event->id,
        ]);
    }

    public function test_cancelled_registration_can_be_reactivated(): void
    {
        $student = $this->student();
        $event = $this->openEvent();

        $registration = EventRegistration::create([
            'user_id' => $student->id,
            'event_id' => $event->id,
            'status' => EventRegistration::STATUS_APPROVED,
            'registered_at' => now(),
        ]);

        $cancel = $this->actingAs($student)
            ->post(route('student.registrations.cancel', $registration));

        $cancel->assertSessionHas('status');
        $this->assertDatabaseHas('event_registrations', [
            'id' => $registration->id,
            'status' => EventRegistration::STATUS_CANCELLED,
        ]);

        $rejoin = $this->actingAs($student)
            ->post(route('student.registrations.store', $event));

        $rejoin->assertSessionHas('status');
        $this->assertDatabaseHas('event_registrations', [
            'id' => $registration->id,
            'status' => EventRegistration::STATUS_PENDING,
        ]);
    }

    public function test_student_cannot_cancel_someone_elses_registration(): void
    {
        $owner = $this->student();
        $event = $this->openEvent();

        $registration = EventRegistration::create([
            'user_id' => $owner->id,
            'event_id' => $event->id,
            'status' => EventRegistration::STATUS_APPROVED,
            'registered_at' => now(),
        ]);

        $intruder = $this->student();

        $this->actingAs($intruder)
            ->post(route('student.registrations.cancel', $registration))
            ->assertStatus(403);

        $this->assertDatabaseHas('event_registrations', [
            'id' => $registration->id,
            'status' => EventRegistration::STATUS_APPROVED,
        ]);
    }
}

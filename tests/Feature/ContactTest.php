<?php

namespace Tests\Feature;

use App\Models\ContactMessage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_view_the_contact_page(): void
    {
        $this->get(route('contact.show'))->assertOk()->assertSee('Send a message');
    }

    public function test_valid_message_is_stored(): void
    {
        $response = $this->post(route('contact.store'), [
            'name' => 'Alex Johnson',
            'email' => 'alex@college.edu',
            'subject' => 'Workshop seats',
            'message' => 'How many seats are left for the workshop next week?',
        ]);

        $response->assertSessionHas('status');
        $this->assertDatabaseHas('contact_messages', [
            'name' => 'Alex Johnson',
            'email' => 'alex@college.edu',
            'is_read' => false,
        ]);
    }

    public function test_invalid_message_shows_validation_errors(): void
    {
        $response = $this->from(route('contact.show'))
            ->post(route('contact.store'), [
                'name' => '',
                'email' => 'not-an-email',
                'message' => 'short',
            ]);

        $response->assertRedirect(route('contact.show'));
        $response->assertSessionHasErrors(['name', 'email', 'message']);
        $this->assertSame(0, ContactMessage::count());
    }

    public function test_admin_can_view_and_delete_messages(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $message = ContactMessage::create([
            'name' => 'Sam Student',
            'email' => 'sam@college.edu',
            'subject' => 'Question',
            'message' => 'This is a longer message body for the test.',
        ]);

        $this->actingAs($admin)->get(route('admin.messages.index'))->assertOk()->assertSee('Sam Student');

        $this->actingAs($admin)->post(route('admin.messages.read', $message))->assertSessionHas('status');
        $this->assertTrue($message->fresh()->is_read);

        $this->actingAs($admin)->delete(route('admin.messages.destroy', $message))->assertSessionHas('status');
        $this->assertDatabaseMissing('contact_messages', ['id' => $message->id]);
    }

    public function test_student_cannot_access_admin_messages(): void
    {
        $student = User::factory()->create(['role' => User::ROLE_STUDENT]);

        $this->actingAs($student)->get(route('admin.messages.index'))->assertStatus(403);
    }
}

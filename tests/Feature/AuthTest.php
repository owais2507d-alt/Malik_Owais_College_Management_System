<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login_from_admin_area(): void
    {
        $this->get('/admin')->assertRedirect(route('login'));
    }

    public function test_guest_is_redirected_to_login_from_student_area(): void
    {
        $this->get('/student/dashboard')->assertRedirect(route('login'));
    }

    public function test_admin_login_lands_on_admin_dashboard(): void
    {
        $admin = User::factory()->create([
            'role' => User::ROLE_ADMIN,
            'password' => 'secret-password',
        ]);

        $response = $this->post('/login', [
            'email' => $admin->email,
            'password' => 'secret-password',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($admin);
    }

    public function test_student_login_lands_on_student_dashboard(): void
    {
        $student = User::factory()->create([
            'role' => User::ROLE_STUDENT,
            'password' => 'secret-password',
        ]);

        $response = $this->post('/login', [
            'email' => $student->email,
            'password' => 'secret-password',
        ]);

        $response->assertRedirect(route('student.dashboard'));
        $this->assertAuthenticatedAs($student);
    }

    public function test_wrong_password_shows_error_and_stays_guest(): void
    {
        $user = User::factory()->create(['password' => 'secret-password']);

        $response = $this->from('/login')->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors();
        $this->assertGuest();
    }

    public function test_student_cannot_access_admin_panel(): void
    {
        $student = User::factory()->create(['role' => User::ROLE_STUDENT]);

        $this->actingAs($student)->get('/admin')->assertStatus(403);
        $this->actingAs($student)->get('/admin/events')->assertStatus(403);
        $this->actingAs($student)->get('/admin/registrations')->assertStatus(403);
        $this->actingAs($student)->get('/admin/categories')->assertStatus(403);
    }

    public function test_admin_cannot_access_student_panel(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);

        $this->actingAs($admin)->get('/student/dashboard')->assertStatus(403);
        $this->actingAs($admin)->get('/student/registrations')->assertStatus(403);
    }

    public function test_new_self_registration_is_always_a_student(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test Student',
            'email' => 'new.student@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect();
        $this->assertAuthenticated();

        $user = User::where('email', 'new.student@example.com')->firstOrFail();
        $this->assertSame(User::ROLE_STUDENT, $user->role);
    }
}

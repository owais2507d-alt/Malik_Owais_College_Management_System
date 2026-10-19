<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventRegistration;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class RegistrationController extends Controller
{
    public function store(Request $request, Event $event): RedirectResponse
    {
        $user = $request->user();

        if ($event->isFull()) {
            return back()->with('error', 'Sorry, "'.$event->title.'" is fully booked.');
        }

        if ($event->registration_deadline->isPast()) {
            return back()->with('error', 'The registration deadline for "'.$event->title.'" has passed.');
        }

        if ($event->status !== Event::STATUS_UPCOMING) {
            return back()->with('error', 'Registration for "'.$event->title.'" is closed.');
        }

        $existing = EventRegistration::query()
            ->where('user_id', $user->id)
            ->where('event_id', $event->id)
            ->first();

        if ($existing) {
            if ($existing->status === EventRegistration::STATUS_CANCELLED) {
                $existing->update([
                    'status' => EventRegistration::STATUS_PENDING,
                    'registered_at' => now(),
                ]);

                return back()->with('status', 'Welcome back! Your registration for "'.$event->title.'" is pending approval.');
            }

            return back()->with('error', 'You have already registered for "'.$event->title.'".');
        }

        try {
            EventRegistration::create([
                'user_id' => $user->id,
                'event_id' => $event->id,
                'status' => EventRegistration::STATUS_PENDING,
                'registered_at' => now(),
            ]);
        } catch (UniqueConstraintViolationException) {
            return back()->with('error', 'You have already registered for "'.$event->title.'".');
        }

        return back()->with('status', 'Registration submitted for "'.$event->title.'" — pending approval.');
    }

    public function cancel(Request $request, EventRegistration $registration): RedirectResponse
    {
        abort_unless($registration->user_id === $request->user()->id, 403);

        if (! $registration->isCancellable()) {
            return back()->with('error', 'This registration can no longer be cancelled.');
        }

        $registration->update(['status' => EventRegistration::STATUS_CANCELLED]);

        return back()->with('status', 'Your registration for "'.$registration->event?->title.'" has been cancelled.');
    }
}

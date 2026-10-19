<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventRegistration;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class RegistrationController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $status = $request->query('status');
        $status = in_array($status, [
            EventRegistration::STATUS_PENDING,
            EventRegistration::STATUS_APPROVED,
            EventRegistration::STATUS_REJECTED,
            EventRegistration::STATUS_CANCELLED,
        ], true) ? $status : null;

        $counts = $user->registrations()
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total');

        $registrations = $user->registrations()
            ->with('event.category')
            ->when($status, fn ($query) => $query->where('status', $status))
            ->orderByDesc('registered_at')
            ->paginate(8)
            ->withQueryString();

        return view('student.registrations', compact('registrations', 'status', 'counts'));
    }

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

                Cache::forget('fiesta.home.stats');

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

        Cache::forget('fiesta.home.stats');

        return back()->with('status', 'Registration submitted for "'.$event->title.'" — pending approval.');
    }

    public function cancel(Request $request, EventRegistration $registration): RedirectResponse
    {
        abort_unless($registration->user_id === $request->user()->id, 403);

        if (! $registration->isCancellable()) {
            return back()->with('error', 'This registration can no longer be cancelled.');
        }

        $registration->update(['status' => EventRegistration::STATUS_CANCELLED]);

        Cache::forget('fiesta.home.stats');

        return back()->with('status', 'Your registration for "'.$registration->event?->title.'" has been cancelled.');
    }
}

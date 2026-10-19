<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventRegistration;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RegistrationController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->query('status');
        $status = in_array($status, ['pending', 'approved', 'rejected', 'cancelled'], true) ? $status : null;

        $eventId = (int) $request->query('event');

        $registrations = EventRegistration::query()
            ->with(['user', 'event.category'])
            ->when($status, fn ($query) => $query->where('status', $status))
            ->when($eventId > 0, fn ($query) => $query->where('event_id', $eventId))
            ->orderByDesc('registered_at')
            ->paginate(12)
            ->withQueryString();

        $events = Event::query()->ordered()->get(['id', 'title', 'event_date']);

        $counts = EventRegistration::query()
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total');

        return view('admin.registrations.index', compact('registrations', 'status', 'events', 'counts'));
    }

    public function approve(EventRegistration $registration): RedirectResponse
    {
        if ($registration->status === EventRegistration::STATUS_CANCELLED) {
            return back()->with('error', 'This registration was cancelled by the student.');
        }

        if ($registration->status === EventRegistration::STATUS_APPROVED) {
            return back()->with('error', 'This registration is already approved.');
        }

        $registration->update(['status' => EventRegistration::STATUS_APPROVED]);

        return back()->with('status', 'Approved: '.$registration->user?->name.' → '.$registration->event?->title);
    }

    public function reject(EventRegistration $registration): RedirectResponse
    {
        if ($registration->status === EventRegistration::STATUS_CANCELLED) {
            return back()->with('error', 'This registration was cancelled by the student.');
        }

        if ($registration->status === EventRegistration::STATUS_REJECTED) {
            return back()->with('error', 'This registration is already rejected.');
        }

        $registration->update(['status' => EventRegistration::STATUS_REJECTED]);

        return back()->with('status', 'Rejected: '.$registration->user?->name.' → '.$registration->event?->title);
    }

    public function participants(Event $event): View
    {
        $registrations = $event->registrations()
            ->with('user')
            ->orderByRaw("FIELD(status, 'pending', 'approved', 'rejected', 'cancelled')")
            ->orderByDesc('registered_at')
            ->paginate(15);

        $taken = $event->seatsTaken();

        return view('admin.events.participants', compact('event', 'registrations', 'taken'));
    }
}

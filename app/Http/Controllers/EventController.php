<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventCategory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class EventController extends Controller
{
    public function index(Request $request): View
    {
        $categories = EventCategory::query()
            ->withCount(['events as events_count' => fn ($query) => $query->whereNull('deleted_at')])
            ->get();

        $status = $request->query('status');
        $status = in_array($status, [Event::STATUS_UPCOMING, Event::STATUS_ONGOING, Event::STATUS_COMPLETED], true)
            ? $status
            : null;

        $categoryId = (int) $request->query('category');

        $events = Event::query()
            ->with('category')
            ->withCount(['registrations as active_registrations' => fn ($query) => $query->active()])
            ->search($request->query('q'))
            ->when($categoryId > 0, fn ($query) => $query->where('category_id', $categoryId))
            ->when($status, fn ($query) => $query->where('status', $status))
            ->ordered()
            ->paginate(9)
            ->withQueryString();

        return view('events.index', [
            'events' => $events,
            'categories' => $categories,
            'status' => $status,
            'categoryId' => $categoryId,
            'q' => $request->query('q'),
        ]);
    }

    public function show(Event $event): View
    {
        $event->load('category');

        $userRegistration = auth()->check()
            ? $event->registrations()->where('user_id', auth()->id())->first()
            : null;

        $related = Event::query()
            ->where('category_id', $event->category_id)
            ->where('id', '!=', $event->id)
            ->whereNull('deleted_at')
            ->with('category')
            ->withCount(['registrations as active_registrations' => fn ($query) => $query->active()])
            ->ordered()
            ->limit(3)
            ->get();

        return view('events.show', compact('event', 'userRegistration', 'related'));
    }

    public function calendar(Event $event): Response
    {
        $escape = fn (string $value): string => str_replace(
            ['\\', ';', ',', "\n"],
            ['\\\\', '\;', '\,', '\n'],
            $value,
        );

        $start = $event->event_date->format('Ymd').'T'.substr((string) $event->start_time, 0, 8);
        $end = $event->event_date->format('Ymd').'T'.substr((string) $event->end_time, 0, 8);
        $now = now()->setTimezone('UTC')->format('Ymd\THis\Z');

        $lines = [
            'BEGIN:VCALENDAR',
            'VERSION:2.0',
            'PRODID:-//Fiesta 2026//College Event Management//EN',
            'CALSCALE:GREGORIAN',
            'BEGIN:VEVENT',
            'UID:event-'.$event->id.'@fiesta.edu',
            'DTSTAMP:'.$now,
            'DTSTART:'.$start,
            'DTEND:'.$end,
            'SUMMARY:'.$escape($event->title),
            'DESCRIPTION:'.$escape($event->description),
            'LOCATION:'.$escape($event->venue),
            'END:VEVENT',
            'END:VCALENDAR',
        ];

        return response(implode("\r\n", $lines)."\r\n", 200, [
            'Content-Type' => 'text/calendar; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="'.$event->slug.'.ics"',
        ]);
    }
}

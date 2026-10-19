<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventCategory;
use Illuminate\Http\Request;
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
}

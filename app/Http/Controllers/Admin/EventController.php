<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Models\Event;
use App\Models\EventCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class EventController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->query('status');
        $status = in_array($status, ['upcoming', 'ongoing', 'completed'], true) ? $status : null;

        $events = Event::query()
            ->with('category')
            ->withCount(['registrations as active_registrations' => fn ($query) => $query->active()])
            ->search($request->query('q'))
            ->when($status, fn ($query) => $query->where('status', $status))
            ->latest('event_date')
            ->paginate(10)
            ->withQueryString();

        return view('admin.events.index', [
            'events' => $events,
            'status' => $status,
            'q' => $request->query('q'),
        ]);
    }

    public function create(): View
    {
        return view('admin.events.form', [
            'event' => new Event,
            'categories' => EventCategory::orderBy('name')->get(),
        ]);
    }

    public function store(StoreEventRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('events', 'public');
        } else {
            unset($data['image']);
        }

        Event::create($data);

        return redirect()
            ->route('admin.events.index')
            ->with('status', 'Event created successfully.');
    }

    public function edit(Event $event): View
    {
        return view('admin.events.form', [
            'event' => $event,
            'categories' => EventCategory::orderBy('name')->get(),
        ]);
    }

    public function update(UpdateEventRequest $request, Event $event): RedirectResponse
    {
        $data = $request->validated();

        if ($request->boolean('_remove_image')) {
            if ($event->image) {
                Storage::disk('public')->delete($event->image);
            }
            $data['image'] = null;
        } elseif ($request->hasFile('image')) {
            if ($event->image) {
                Storage::disk('public')->delete($event->image);
            }
            $data['image'] = $request->file('image')->store('events', 'public');
        } else {
            unset($data['image']);
        }

        if ($event->title !== $data['title']) {
            $data['slug'] = Event::uniqueSlug($data['title'], $event->id);
        }

        $event->update($data);

        return redirect()
            ->route('admin.events.index')
            ->with('status', 'Event updated successfully.');
    }

    public function destroy(Event $event): RedirectResponse
    {
        if ($event->image) {
            Storage::disk('public')->delete($event->image);
        }

        $event->delete();

        return redirect()
            ->route('admin.events.index')
            ->with('status', 'Event "'.$event->title.'" deleted.');
    }
}

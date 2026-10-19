<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventCategory;
use App\Models\EventRegistration;
use App\Models\User;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $stats = [
            'events' => Event::count(),
            'students' => User::where('role', User::ROLE_STUDENT)->count(),
            'registrations' => EventRegistration::where('status', '!=', 'cancelled')->count(),
            'categories' => EventCategory::count(),
        ];

        $highlights = Event::query()
            ->upcoming()
            ->with('category')
            ->ordered()
            ->limit(6)
            ->get();

        $categories = EventCategory::query()
            ->withCount(['events as events_count' => fn ($query) => $query->whereNull('deleted_at')])
            ->get();

        return view('home', compact('stats', 'highlights', 'categories'));
    }
}

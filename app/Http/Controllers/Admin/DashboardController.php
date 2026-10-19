<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'events' => Event::count(),
            'students' => User::where('role', User::ROLE_STUDENT)->count(),
            'registrations' => EventRegistration::active()->count(),
            'pending' => EventRegistration::pending()->count(),
        ];

        $upcoming = Event::query()
            ->upcoming()
            ->with('category')
            ->withCount(['registrations as active_registrations' => fn ($query) => $query->active()])
            ->ordered()
            ->limit(6)
            ->get();

        $recent = EventRegistration::query()
            ->with(['user', 'event'])
            ->latest('created_at')
            ->limit(7)
            ->get();

        $statusCounts = EventRegistration::query()
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total');

        return view('admin.dashboard', compact('stats', 'upcoming', 'recent', 'statusCounts'));
    }
}

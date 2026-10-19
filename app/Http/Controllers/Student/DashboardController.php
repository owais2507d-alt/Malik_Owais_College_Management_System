<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\EventRegistration;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        $registrations = $user->registrations();

        $stats = [
            'total' => (clone $registrations)->count(),
            'pending' => (clone $registrations)->pending()->count(),
            'approved' => (clone $registrations)->approved()->count(),
            'upcoming' => (clone $registrations)
                ->active()
                ->whereHas('event', function ($query) {
                    $query->where('event_date', '>=', today())
                        ->whereIn('status', ['upcoming', 'ongoing']);
                })
                ->count(),
        ];

        $nextEvents = (clone $registrations)
            ->active()
            ->whereHas('event', function ($query) {
                $query->whereNull('deleted_at')
                    ->where('event_date', '>=', today());
            })
            ->with('event.category')
            ->get()
            ->sortBy('event.event_date')
            ->values()
            ->take(5);

        $recent = (clone $registrations)
            ->with('event')
            ->latest('registered_at')
            ->take(5)
            ->get();

        return view('student.dashboard', compact('user', 'stats', 'nextEvents', 'recent'));
    }
}

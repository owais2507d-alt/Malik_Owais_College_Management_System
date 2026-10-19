@extends('layouts.app')

@section('title', 'International Fiesta 2026 — Voices Unmuted')

@section('content')
{{-- Hero --}}
<section class="relative overflow-hidden bg-sidebar text-white">
    <div class="absolute -left-32 -top-32 h-96 w-96 rounded-full bg-brand-500/30 blur-3xl"></div>
    <div class="absolute -right-24 top-16 h-80 w-80 rounded-full bg-fuchsia-500/25 blur-3xl"></div>
    <div class="absolute bottom-0 left-1/3 h-64 w-64 rounded-full bg-amber-500/15 blur-3xl"></div>

    <div class="relative mx-auto grid max-w-7xl gap-10 px-4 pb-28 pt-16 sm:px-6 lg:grid-cols-2 lg:items-center lg:px-8 lg:pb-32 lg:pt-24">
        <div>
            <span class="inline-flex items-center gap-2 rounded-full bg-white/10 px-3.5 py-1.5 text-xs font-semibold uppercase tracking-[0.2em] text-white/80 ring-1 ring-white/20">
                <span class="h-1.5 w-1.5 animate-pulse rounded-full bg-amber-400"></span>
                International Fiesta 2026
            </span>

            <h1 class="mt-6 text-4xl font-bold leading-[1.08] tracking-tight sm:text-5xl xl:text-6xl">
                Voices Unmuted:<br>
                <span class="text-spectrum">The Spectrum</span>
            </h1>

            <p class="mt-5 max-w-lg text-base leading-relaxed text-white/75 sm:text-lg">
                From hackathons to open-mic nights — discover every college event, grab your seat,
                and track your registrations from one vibrant platform.
            </p>

            <div class="mt-8 flex flex-wrap gap-3">
                <a href="{{ route('events.index') }}" class="btn bg-white px-6 py-3 text-sm font-bold text-brand-700 shadow-lg hover:bg-white/90 focus:ring-white">
                    Explore events
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                </a>
                @guest
                    <a href="{{ route('register') }}" class="btn border border-white/30 px-6 py-3 text-sm font-semibold text-white hover:bg-white/10 focus:ring-white">
                        Create free account
                    </a>
                @else
                    @if (auth()->user()->isAdmin() && Route::has('admin.dashboard'))
                        <a href="{{ route('admin.dashboard') }}" class="btn border border-white/30 px-6 py-3 text-sm font-semibold text-white hover:bg-white/10 focus:ring-white">Open Admin Panel</a>
                    @elseif (Route::has('student.dashboard'))
                        <a href="{{ route('student.dashboard') }}" class="btn border border-white/30 px-6 py-3 text-sm font-semibold text-white hover:bg-white/10 focus:ring-white">My Dashboard</a>
                    @endif
                @endauth
            </div>

            <div class="mt-8 flex flex-wrap gap-x-6 gap-y-2 text-sm text-white/60">
                <span class="flex items-center gap-1.5">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                    {{ $stats['events'] }} events on the calendar
                </span>
                <span class="flex items-center gap-1.5">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/></svg>
                    {{ $stats['students'] }} students registered
                </span>
            </div>
        </div>

        {{-- Floating preview card --}}
        @if ($highlights->isNotEmpty())
            @php $next = $highlights->first(); @endphp
            <div class="relative mx-auto w-full max-w-sm lg:max-w-md">
                <div class="rotate-[-2deg] rounded-3xl border border-white/15 bg-white/10 p-5 backdrop-blur-xl shadow-2xl shadow-black/30">
                    <div class="flex items-center justify-between text-xs font-semibold uppercase tracking-widest text-white/60">
                        <span>Next up</span>
                        <span class="badge-upcoming bg-white/15 text-white ring-white/20">{{ ucfirst($next->status) }}</span>
                    </div>
                    <p class="mt-3 text-lg font-bold leading-snug">{{ $next->title }}</p>
                    <div class="mt-3 grid grid-cols-2 gap-3 text-sm text-white/75">
                        <p class="rounded-xl bg-black/20 px-3 py-2">{{ $next->event_date->format('D, M j, Y') }}</p>
                        <p class="rounded-xl bg-black/20 px-3 py-2">{{ \Illuminate\Support\Carbon::parse($next->start_time)->format('g:i A') }}</p>
                    </div>
                    <p class="mt-3 flex items-center gap-2 text-sm text-white/75">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                        {{ $next->venue }}
                    </p>
                    <a href="{{ route('events.show', $next) }}" class="btn mt-4 w-full bg-white text-sm font-bold text-brand-700 hover:bg-white/90">
                        View event
                    </a>
                </div>
            </div>
        @endif
    </div>

    <div class="absolute bottom-0 left-0 right-0 h-1.5 gradient-spectrum"></div>
</section>

{{-- Stats strip --}}
<section class="relative z-10 mx-auto -mt-12 max-w-7xl px-4 sm:px-6 lg:px-8">
    <div class="grid grid-cols-2 gap-3 sm:gap-4 lg:grid-cols-4">
        @foreach ([
            ['label' => 'Total events', 'value' => $stats['events'], 'icon' => '<rect width="18" height="18" x="3" y="4" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/>'],
            ['label' => 'Students', 'value' => $stats['students'], 'icon' => '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/>'],
            ['label' => 'Registrations', 'value' => $stats['registrations'], 'icon' => '<path d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01"/>'],
            ['label' => 'Categories', 'value' => $stats['categories'], 'icon' => '<path d="M4 7V4h16v3M9 20h6M12 4v16"/>'],
        ] as $stat)
            <div class="card flex items-center gap-3 px-4 py-4 sm:px-5 sm:py-5">
                <span class="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-brand-50 text-brand-600">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">{!! $stat['icon'] !!}</svg>
                </span>
                <div class="min-w-0">
                    <p class="text-xl font-bold text-slate-900 sm:text-2xl">{{ $stat['value'] }}</p>
                    <p class="truncate text-xs text-slate-500 sm:text-sm">{{ $stat['label'] }}</p>
                </div>
            </div>
        @endforeach
    </div>
</section>

{{-- Highlights --}}
<section class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8 lg:py-20">
    <div class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <p class="text-sm font-semibold uppercase tracking-widest text-brand-600">Coming up</p>
            <h2 class="mt-1.5 text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">Event highlights</h2>
            <p class="mt-2 max-w-xl text-slate-500">The next events on the Fiesta calendar. Seats are limited — claim yours early.</p>
        </div>
        <a href="{{ route('events.index') }}" class="btn-secondary">
            View all events
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
        </a>
    </div>

    @if ($highlights->isEmpty())
        <div class="card mt-8 p-10 text-center text-slate-500">No upcoming events yet — check back soon.</div>
    @else
        <div class="mt-8 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($highlights as $event)
                @include('partials.event-card', ['event' => $event])
            @endforeach
        </div>
    @endif
</section>

{{-- Categories band --}}
<section class="border-t border-slate-200 bg-white">
    <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
        <p class="text-sm font-semibold uppercase tracking-widest text-brand-600">Browse by</p>
        <h2 class="mt-1.5 text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">Event categories</h2>

        <div class="mt-7 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($categories as $category)
                <a href="{{ route('events.index', ['category' => $category->id]) }}"
                   class="group card flex items-start gap-4 p-5 transition hover:-translate-y-0.5 hover:border-brand-300 hover:shadow-md">
                    <span class="gradient-spectrum grid h-11 w-11 shrink-0 place-items-center rounded-xl text-white shadow-sm transition group-hover:scale-105">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 7V4h16v3M9 20h6M12 4v16"/></svg>
                    </span>
                    <div>
                        <h3 class="font-semibold text-slate-900 transition group-hover:text-brand-600">{{ $category->name }}</h3>
                        <p class="mt-1 line-clamp-2 text-sm text-slate-500">{{ $category->description }}</p>
                        <p class="mt-2 text-xs font-semibold text-brand-600">{{ $category->events_count }} event{{ $category->events_count === 1 ? '' : 's' }}</p>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endsection

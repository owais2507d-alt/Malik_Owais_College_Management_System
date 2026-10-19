@extends('layouts.app')

@section('title', 'International Fiesta 2026 — Voices Unmuted')

@section('content')
{{-- Hero --}}
<section class="relative overflow-hidden bg-sidebar text-white">
    <img src="{{ asset('images/hero-fiesta.jpg') }}" alt=""
         class="absolute inset-0 h-full w-full object-cover opacity-30" fetchpriority="high">
    <div class="absolute inset-0 bg-gradient-to-r from-sidebar via-sidebar/85 to-sidebar/40"></div>
    <div class="absolute inset-0 bg-gradient-to-t from-sidebar via-transparent to-transparent"></div>

    <div class="anim-blob absolute -left-32 -top-32 h-96 w-96 rounded-full bg-brand-500/30 blur-3xl"></div>
    <div class="anim-blob absolute -right-24 top-16 h-80 w-80 rounded-full bg-fuchsia-500/25 blur-3xl" style="animation-delay: -5s"></div>
    <div class="anim-blob absolute bottom-0 left-1/3 h-64 w-64 rounded-full bg-amber-500/15 blur-3xl" style="animation-delay: -9s"></div>

    <div class="relative mx-auto grid max-w-7xl gap-10 px-4 pb-28 pt-16 sm:px-6 lg:grid-cols-2 lg:items-center lg:px-8 lg:pb-32 lg:pt-24">
        <div>
            <span class="anim-fade-up inline-flex items-center gap-2 rounded-full bg-white/10 px-3.5 py-1.5 text-xs font-semibold uppercase tracking-[0.2em] text-white/80 ring-1 ring-white/20 backdrop-blur">
                <span class="anim-dot h-1.5 w-1.5 rounded-full bg-amber-400"></span>
                International Fiesta 2026
            </span>

            <h1 class="anim-fade-up mt-6 text-4xl font-bold leading-[1.08] tracking-tight sm:text-5xl xl:text-6xl" style="animation-delay: 80ms">
                Voices Unmuted:<br>
                <span class="text-spectrum">The Spectrum</span>
            </h1>

            <p class="anim-fade-up mt-5 max-w-lg text-base leading-relaxed text-white/75 sm:text-lg" style="animation-delay: 160ms">
                From hackathons to open-mic nights — discover every college event, grab your seat,
                and track your registrations from one vibrant platform.
            </p>

            <div class="anim-fade-up mt-8 flex flex-wrap gap-3" style="animation-delay: 240ms">
                <a href="{{ route('events.index') }}" class="btn shine bg-white px-6 py-3 text-sm font-bold text-brand-700 shadow-lg hover:bg-white/90 focus:ring-white">
                    Explore events
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                </a>
                @guest
                    <a href="{{ route('register') }}" class="btn border border-white/30 px-6 py-3 text-sm font-semibold text-white transition hover:scale-[1.03] hover:bg-white/10 focus:ring-white">
                        Create free account
                    </a>
                @else
                    @if (auth()->user()->isAdmin() && Route::has('admin.dashboard'))
                        <a href="{{ route('admin.dashboard') }}" class="btn border border-white/30 px-6 py-3 text-sm font-semibold text-white transition hover:scale-[1.03] hover:bg-white/10 focus:ring-white">Open Admin Panel</a>
                    @elseif (Route::has('student.dashboard'))
                        <a href="{{ route('student.dashboard') }}" class="btn border border-white/30 px-6 py-3 text-sm font-semibold text-white transition hover:scale-[1.03] hover:bg-white/10 focus:ring-white">My Dashboard</a>
                    @endif
                @endauth
            </div>

            <div class="anim-fade-up mt-8 flex flex-wrap gap-x-6 gap-y-2 text-sm text-white/60" style="animation-delay: 320ms">
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
                <div class="anim-float anim-fade-up rounded-3xl border border-white/15 bg-white/10 p-5 shadow-2xl shadow-black/30 backdrop-blur-xl" style="animation-delay: 200ms">
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
                    <a href="{{ route('events.show', $next) }}" class="btn shine mt-4 w-full bg-white text-sm font-bold text-brand-700 hover:bg-white/90">
                        View event
                    </a>
                </div>
            </div>
        @endif
    </div>

    <div class="absolute bottom-0 left-0 right-0 h-1.5 gradient-spectrum-animated"></div>
</section>

{{-- Stats strip --}}
<section class="relative z-10 mx-auto -mt-12 max-w-7xl px-4 sm:px-6 lg:px-8">
    <div class="grid grid-cols-2 gap-3 sm:gap-4 lg:grid-cols-4">
        @foreach ([
            ['label' => 'Total events', 'value' => $stats['events'], 'icon' => '<rect width="18" height="18" x="3" y="4" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/>'],
            ['label' => 'Students', 'value' => $stats['students'], 'icon' => '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/>'],
            ['label' => 'Registrations', 'value' => $stats['registrations'], 'icon' => '<path d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01"/>'],
            ['label' => 'Categories', 'value' => $stats['categories'], 'icon' => '<path d="M4 7V4h16v3M9 20h6M12 4v16"/>'],
        ] as $i => $stat)
            <div class="card reveal flex items-center gap-3 px-4 py-4 transition hover:-translate-y-0.5 hover:shadow-md sm:px-5 sm:py-5" style="--reveal-delay: {{ $i * 90 }}ms">
                <span class="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-brand-50 text-brand-600 dark:bg-brand-500/15 dark:text-brand-400">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">{!! $stat['icon'] !!}</svg>
                </span>
                <div class="min-w-0">
                    <p class="text-xl font-bold text-slate-900 dark:text-white sm:text-2xl">{{ $stat['value'] }}</p>
                    <p class="truncate text-xs text-slate-500 dark:text-slate-400 sm:text-sm">{{ $stat['label'] }}</p>
                </div>
            </div>
        @endforeach
    </div>
</section>

{{-- Highlights --}}
<section class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8 lg:py-20">
    <div class="reveal flex flex-wrap items-end justify-between gap-4">
        <div>
            <p class="text-sm font-semibold uppercase tracking-widest text-brand-600 dark:text-brand-400">Coming up</p>
            <h2 class="mt-1.5 text-2xl font-bold tracking-tight text-slate-900 dark:text-white sm:text-3xl">Event highlights</h2>
            <p class="mt-2 max-w-xl text-slate-500 dark:text-slate-400">The next events on the Fiesta calendar. Seats are limited — claim yours early.</p>
        </div>
        <a href="{{ route('events.index') }}" class="btn-secondary group">
            View all events
            <svg class="h-4 w-4 transition group-hover:translate-x-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
        </a>
    </div>

    @if ($highlights->isEmpty())
        <div class="card mt-8 p-10 text-center text-slate-500 dark:text-slate-400">No upcoming events yet — check back soon.</div>
    @else
        <div class="mt-8 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($highlights as $i => $event)
                <div class="reveal" style="--reveal-delay: {{ $i * 100 }}ms">
                    @include('partials.event-card', ['event' => $event])
                </div>
            @endforeach
        </div>
    @endif
</section>

{{-- Why Fiesta --}}
<section class="border-y border-slate-200 bg-white dark:border-slate-800 dark:bg-slate-900/40">
    <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
        <div class="reveal mx-auto max-w-2xl text-center">
            <p class="text-sm font-semibold uppercase tracking-widest text-brand-600 dark:text-brand-400">Why Fiesta</p>
            <h2 class="mt-1.5 text-2xl font-bold tracking-tight text-slate-900 dark:text-white sm:text-3xl">Built for campus life</h2>
            <p class="mt-2 text-slate-500 dark:text-slate-400">Everything students and organizers need — nothing they don't.</p>
        </div>

        <div class="mt-10 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ([
                ['t' => 'One-tap register', 'd' => 'Spot an event you love? Claim your seat instantly — approvals land in your dashboard.', 'i' => '<path d="M20 6 9 17l-5-5"/>', 'g' => 'from-violet-500 to-fuchsia-500'],
                ['t' => 'Live capacity', 'd' => 'Real-time seat counters and deadlines so you never miss out on a full house.', 'i' => '<path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>', 'g' => 'from-sky-500 to-indigo-500'],
                ['t' => 'Track everything', 'd' => 'Pending, approved, rejected — every registration status is one glance away.', 'i' => '<circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/>', 'g' => 'from-teal-500 to-emerald-500'],
                ['t' => 'Add to calendar', 'd' => 'Download a one-click .ics invite for every event you plan to attend.', 'i' => '<rect width="18" height="18" x="3" y="4" rx="2"/><path d="M16 2v4M8 2v4M3 10h18M8 14h.01M12 14h.01M16 14h.01M8 18h.01M12 18h.01M16 18h.01"/>', 'g' => 'from-amber-500 to-rose-500'],
            ] as $i => $f)
                <div class="reveal card group p-5 transition hover:-translate-y-1 hover:shadow-lg" style="--reveal-delay: {{ $i * 90 }}ms">
                    <span class="grid h-11 w-11 place-items-center rounded-xl bg-gradient-to-br {{ $f['g'] }} text-white shadow-md transition group-hover:scale-110">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">{!! $f['i'] !!}</svg>
                    </span>
                    <h3 class="mt-4 font-bold text-slate-900 dark:text-white">{{ $f['t'] }}</h3>
                    <p class="mt-1.5 text-sm leading-relaxed text-slate-500 dark:text-slate-400">{{ $f['d'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Categories band --}}
<section class="border-t border-slate-200 bg-white dark:border-slate-800 dark:bg-slate-950">
    <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
        <div class="reveal">
            <p class="text-sm font-semibold uppercase tracking-widest text-brand-600 dark:text-brand-400">Browse by</p>
            <h2 class="mt-1.5 text-2xl font-bold tracking-tight text-slate-900 dark:text-white sm:text-3xl">Event categories</h2>
        </div>

        <div class="mt-7 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($categories as $i => $category)
                <a href="{{ route('events.index', ['category' => $category->id]) }}"
                   class="reveal group card flex items-start gap-4 p-5 transition hover:-translate-y-0.5 hover:border-brand-300 hover:shadow-md dark:hover:border-brand-500/40"
                   style="--reveal-delay: {{ $i * 70 }}ms">
                    <span class="gradient-spectrum grid h-11 w-11 shrink-0 place-items-center rounded-xl text-white shadow-sm transition group-hover:scale-110">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 7V4h16v3M9 20h6M12 4v16"/></svg>
                    </span>
                    <div>
                        <h3 class="font-semibold text-slate-900 transition group-hover:text-brand-600 dark:text-white dark:group-hover:text-brand-400">{{ $category->name }}</h3>
                        <p class="mt-1 line-clamp-2 text-sm text-slate-500 dark:text-slate-400">{{ $category->description }}</p>
                        <p class="mt-2 text-xs font-semibold text-brand-600 dark:text-brand-400">{{ $category->events_count }} event{{ $category->events_count === 1 ? '' : 's' }}</p>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>

{{-- Contact CTA --}}
<section class="relative overflow-hidden">
    <div class="gradient-spectrum-animated absolute inset-0"></div>
    <div class="absolute -left-16 -bottom-20 h-64 w-64 rounded-full bg-white/10 blur-3xl"></div>
    <div class="relative mx-auto flex max-w-7xl flex-col items-start justify-between gap-6 px-4 py-12 text-white sm:px-6 lg:flex-row lg:items-center lg:px-8">
        <div class="reveal">
            <h2 class="text-2xl font-bold tracking-tight sm:text-3xl">Got a question — or an event idea?</h2>
            <p class="mt-2 max-w-xl text-white/85">Talk to the Fiesta team about registrations, hosting your own event, or anything in between.</p>
        </div>
        <a href="{{ route('contact.show') }}" class="btn shine shrink-0 bg-white px-6 py-3 text-sm font-bold text-brand-700 shadow-lg hover:bg-white/90 focus:ring-white">
            Contact us
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
        </a>
    </div>
</section>
@endsection

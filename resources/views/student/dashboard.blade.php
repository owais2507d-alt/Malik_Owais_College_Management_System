@extends('layouts.app')

@section('title', 'My Dashboard')

@section('content')
@include('partials.student-nav')

<div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
    <div class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <p class="text-sm font-semibold uppercase tracking-widest text-brand-600 dark:text-brand-400">Student panel</p>
            <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-900 dark:text-white sm:text-3xl">
                Welcome back, {{ \Illuminate\Support\Str::words($user->name, 1, '') }}
            </h1>
            <p class="mt-1 text-slate-500 dark:text-slate-400">Here's what's happening with your Fiesta registrations.</p>
        </div>
        <a href="{{ route('events.index') }}" class="btn-primary">
            Browse events
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
        </a>
    </div>

    {{-- Stats --}}
    <div class="mt-7 grid grid-cols-2 gap-4 lg:grid-cols-4">
        @foreach ([
            ['label' => 'My registrations', 'value' => $stats['total'], 'class' => 'bg-brand-50 text-brand-600 dark:text-brand-400'],
            ['label' => 'Pending approval', 'value' => $stats['pending'], 'class' => 'bg-pending-50 text-pending-600 dark:text-amber-300'],
            ['label' => 'Approved', 'value' => $stats['approved'], 'class' => 'bg-approved-50 text-approved-600 dark:text-emerald-300'],
            ['label' => 'Upcoming events', 'value' => $stats['upcoming'], 'class' => 'bg-teal-50 text-teal-600'],
        ] as $stat)
            <div class="card px-5 py-5">
                <span class="grid h-9 w-9 place-items-center rounded-lg {{ $stat['class'] }}">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01"/></svg>
                </span>
                <p class="mt-3 text-2xl font-bold text-slate-900 dark:text-white">{{ $stat['value'] }}</p>
                <p class="text-sm text-slate-500 dark:text-slate-400">{{ $stat['label'] }}</p>
            </div>
        @endforeach
    </div>

    <div class="mt-6 grid gap-6 lg:grid-cols-3">
        {{-- Next events --}}
        <div class="lg:col-span-2">
            <div class="card overflow-hidden">
                <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-800 px-6 py-4">
                    <h2 class="font-bold text-slate-900 dark:text-white">My next events</h2>
                    <a href="{{ route('student.registrations.index') }}" class="text-sm font-semibold text-brand-600 dark:text-brand-400 hover:text-brand-700 dark:text-brand-300">View all →</a>
                </div>

                @if ($nextEvents->isEmpty())
                    <div class="px-6 py-10 text-center">
                        <p class="text-sm text-slate-500 dark:text-slate-400">No upcoming events yet.</p>
                        <a href="{{ route('events.index') }}" class="btn-secondary mt-4 inline-flex">Find something fun</a>
                    </div>
                @else
                    <ul class="divide-y divide-slate-100">
                        @foreach ($nextEvents as $registration)
                            @php $event = $registration->event; @endphp
                            @if ($event)
                                <li class="flex flex-wrap items-center gap-3 px-6 py-4 transition hover:bg-slate-50/70">
                                    <span class="grid h-11 w-11 shrink-0 place-items-center rounded-xl bg-brand-50 text-sm font-bold text-brand-700 dark:text-brand-300">
                                        {{ $event->event_date->format('j') }}
                                        <span class="block text-[10px] font-semibold uppercase">{{ $event->event_date->format('M') }}</span>
                                    </span>
                                    <div class="min-w-0 flex-1">
                                        <a href="{{ route('events.show', $event) }}" class="block truncate text-sm font-semibold text-slate-900 dark:text-white hover:text-brand-600 dark:text-brand-400">{{ $event->title }}</a>
                                        <p class="truncate text-xs text-slate-500 dark:text-slate-400">{{ $event->venue }} · {{ \Illuminate\Support\Carbon::parse($event->start_time)->format('g:i A') }}</p>
                                    </div>
                                    <span class="badge-{{ $registration->status }}">{{ ucfirst($registration->status) }}</span>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>

        {{-- Recent activity --}}
        <div class="card overflow-hidden">
            <div class="border-b border-slate-100 dark:border-slate-800 px-6 py-4">
                <h2 class="font-bold text-slate-900 dark:text-white">Recent activity</h2>
            </div>
            @if ($recent->isEmpty())
                <p class="px-6 py-8 text-center text-sm text-slate-500 dark:text-slate-400">Nothing here yet — register for your first event.</p>
            @else
                <ul class="divide-y divide-slate-100">
                    @foreach ($recent as $registration)
                        <li class="px-6 py-3.5">
                            <p class="truncate text-sm font-medium text-slate-800 dark:text-slate-100">{{ $registration->event?->title ?? 'Deleted event' }}</p>
                            <div class="mt-1 flex items-center justify-between gap-2">
                                <span class="badge-{{ $registration->status }}">{{ ucfirst($registration->status) }}</span>
                                <span class="text-xs text-slate-400 dark:text-slate-500">{{ $registration->registered_at->diffForHumans() }}</span>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</div>
@endsection

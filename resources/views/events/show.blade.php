@extends('layouts.app')

@section('title', $event->title)

@php
    $taken = $event->seatsTaken();
    $remaining = max(0, $event->max_participants - $taken);
    $percent = $event->max_participants > 0 ? min(100, (int) ceil($taken / $event->max_participants * 100)) : 0;
    $deadlinePassed = $event->registration_deadline->isPast();
@endphp

@section('content')
<div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8 lg:py-12">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-sm text-slate-500 dark:text-slate-400">
        <a href="{{ route('events.index') }}" class="hover:text-brand-600 dark:text-brand-400">Events</a>
        <span class="text-slate-300">/</span>
        <span class="truncate font-medium text-slate-700 dark:text-slate-200">{{ $event->title }}</span>
    </nav>

    {{-- Header --}}
    <header class="mt-5">
        <div class="flex flex-wrap items-center gap-2">
            @if ($event->category)
                <a href="{{ route('events.index', ['category' => $event->category_id]) }}" class="badge-category hover:bg-brand-50 hover:text-brand-700 dark:text-brand-300">
                    {{ $event->category->name }}
                </a>
            @endif
            <span class="badge-{{ $event->status }}">{{ ucfirst($event->status) }}</span>
            @if ($remaining <= 0)
                <span class="badge-rejected">Fully booked</span>
            @elseif ($deadlinePassed)
                <span class="badge-pending">Registration closed</span>
            @endif
        </div>

        <h1 class="mt-3 text-3xl font-bold tracking-tight text-slate-900 dark:text-white sm:text-4xl">{{ $event->title }}</h1>

        <div class="mt-4 flex flex-wrap gap-x-5 gap-y-2 text-sm text-slate-600 dark:text-slate-300">
            <span class="flex items-center gap-2">
                <svg class="h-5 w-5 text-brand-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                {{ $event->event_date->format('l, F j, Y') }}
            </span>
            <span class="flex items-center gap-2">
                <svg class="h-5 w-5 text-brand-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                {{ \Illuminate\Support\Carbon::parse($event->start_time)->format('g:i A') }} – {{ \Illuminate\Support\Carbon::parse($event->end_time)->format('g:i A') }}
            </span>
            <span class="flex items-center gap-2">
                <svg class="h-5 w-5 text-brand-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                {{ $event->venue }}
            </span>
        </div>
    </header>

    <div class="mt-8 grid gap-6 lg:grid-cols-3">
        {{-- Main --}}
        <div class="space-y-6 lg:col-span-2">
            <div class="card p-6 sm:p-7">
                <h2 class="text-lg font-bold text-slate-900 dark:text-white">About this event</h2>
                <div class="mt-3 leading-relaxed text-slate-600 dark:text-slate-300 [&>p]:mt-3 [&>p:first-child]:mt-0">
                    {!! nl2br(e($event->description)) !!}
                </div>
            </div>

            <div class="card grid grid-cols-2 gap-px overflow-hidden bg-slate-200/60 sm:grid-cols-3">
                @foreach ([
                    ['label' => 'Category', 'value' => $event->category?->name ?? '—'],
                    ['label' => 'Status', 'value' => ucfirst($event->status)],
                    ['label' => 'Capacity', 'value' => $event->max_participants . ' seats'],
                    ['label' => 'Deadline', 'value' => $event->registration_deadline->format('M j, Y')],
                    ['label' => 'Registered', 'value' => $taken . ' / ' . $event->max_participants],
                    ['label' => 'Entry', 'value' => 'Free'],
                ] as $cell)
                    <div class="bg-white dark:bg-slate-900 px-5 py-4">
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">{{ $cell['label'] }}</p>
                        <p class="mt-1 text-sm font-semibold text-slate-800 dark:text-slate-100">{{ $cell['value'] }}</p>
                    </div>
                @endforeach
            </div>

            @if ($related->isNotEmpty())
                <div>
                    <h2 class="text-lg font-bold text-slate-900 dark:text-white">More from {{ $event->category?->name ?? 'this category' }}</h2>
                    <div class="mt-4 grid gap-5 sm:grid-cols-2 xl:grid-cols-3">
                        @foreach ($related as $sibling)
                            @include('partials.event-card', ['event' => $sibling])
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        {{-- Sidebar --}}
        <aside class="space-y-5 lg:sticky lg:top-24 lg:self-start">
            <div class="card p-6">
                <div class="flex items-baseline justify-between">
                    <h2 class="font-bold text-slate-900 dark:text-white">Seats</h2>
                    <p class="text-sm {{ $remaining <= 0 ? 'font-semibold text-rejected-600 dark:text-red-300' : 'text-slate-500 dark:text-slate-400' }}">
                        @if ($remaining <= 0)
                            Fully booked
                        @else
                            <span class="font-bold text-slate-900 dark:text-white">{{ $remaining }}</span> of {{ $event->max_participants }} left
                        @endif
                    </p>
                </div>

                <div class="mt-3 h-2.5 w-full overflow-hidden rounded-full bg-slate-100 dark:bg-slate-800">
                    <div class="h-full rounded-full {{ $percent >= 100 ? 'bg-rejected-500' : ($percent >= 75 ? 'bg-amber-500' : 'gradient-spectrum') }} transition-all"
                         style="width: {{ $percent }}%"></div>
                </div>
                <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">{{ $taken }} of {{ $event->max_participants }} seats filled ({{ $percent }}%)</p>

                <div class="mt-4 space-y-2.5 border-t border-slate-100 dark:border-slate-800 pt-4 text-sm">
                    <p class="flex items-start gap-2 text-slate-600 dark:text-slate-300">
                        <svg class="mt-0.5 h-4 w-4 shrink-0 text-slate-400 dark:text-slate-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                        <span>Event on <strong class="font-semibold text-slate-800 dark:text-slate-100">{{ $event->event_date->format('M j, Y') }}</strong></span>
                    </p>
                    <p class="flex items-start gap-2 {{ $deadlinePassed ? 'text-rejected-600 dark:text-red-300' : 'text-slate-600 dark:text-slate-300' }}">
                        <svg class="mt-0.5 h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                        <span>Registration {{ $deadlinePassed ? 'closed' : 'closes' }} <strong class="font-semibold">{{ $event->registration_deadline->format('M j, Y · g:i A') }}</strong></span>
                    </p>
                </div>

                <a href="{{ route('events.calendar', $event) }}"
                   class="btn-secondary mt-4 w-full">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M16 2v4M8 2v4M3 10h18M12 14v4M10 16h4"/></svg>
                    Add to calendar (.ics)
                </a>

                {{-- CTA --}}
                <div class="mt-5 border-t border-slate-100 dark:border-slate-800 pt-4">
                    @guest
                        <p class="text-sm text-slate-500 dark:text-slate-400">Sign in with your student account to grab a seat.</p>
                        <a href="{{ route('login') }}" class="btn-primary mt-3 w-full">Sign in to register</a>
                        <a href="{{ route('register') }}" class="btn-secondary mt-2 w-full">Create an account</a>
                    @else
                        @if (auth()->user()->isAdmin())
                            <p class="text-sm text-slate-500 dark:text-slate-400">You're signed in as an admin — students register for events from their own panel.</p>
                        @elseif ($userRegistration)
                            <div class="flex items-center justify-between gap-2">
                                <div>
                                    <p class="text-sm font-semibold text-slate-800 dark:text-slate-100">Your registration</p>
                                    <span class="badge-{{ $userRegistration->status }} mt-1.5">{{ ucfirst($userRegistration->status) }}</span>
                                </div>
                                <span class="grid h-10 w-10 place-items-center rounded-full {{ $userRegistration->status === 'approved' ? 'bg-approved-50 text-approved-600 dark:text-emerald-300' : ($userRegistration->status === 'rejected' ? 'bg-rejected-50 text-rejected-600 dark:text-red-300' : 'bg-pending-50 text-pending-600 dark:text-amber-300') }}">
                                    @if ($userRegistration->status === 'approved')
                                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                                    @elseif ($userRegistration->status === 'rejected')
                                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18M6 6l12 12"/></svg>
                                    @else
                                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                                    @endif
                                </span>
                            </div>

                            @if ($userRegistration->status === 'pending')
                                <p class="mt-3 text-xs text-slate-500 dark:text-slate-400">Waiting for admin approval. You'll see the update in <a href="{{ Route::has('student.registrations.index') ? route('student.registrations.index') : '#' }}" class="font-semibold text-brand-600 dark:text-brand-400">My Registrations</a>.</p>
                            @elseif ($userRegistration->status === 'approved')
                                <p class="mt-3 text-xs text-approved-600 dark:text-emerald-300">You're in! Arrive at the venue on time with your college ID.</p>
                            @elseif ($userRegistration->status === 'rejected')
                                <p class="mt-3 text-xs text-rejected-600 dark:text-red-300">Your request was not approved. You may contact the event admin for details.</p>
                            @endif

                            @if ($userRegistration->isCancellable())
                                <div x-data="{ confirming: false }" class="mt-4">
                                    <button type="button" x-show="!confirming" @click="confirming = true"
                                            class="btn-ghost w-full text-rejected-600 dark:text-red-300 hover:bg-rejected-50">
                                        Cancel registration
                                    </button>
                                    <div x-show="confirming" x-cloak x-transition class="rounded-xl bg-rejected-50 p-3 text-center">
                                        <p class="text-xs font-medium text-rejected-600 dark:text-red-300">Cancel this registration? A seat opens for others.</p>
                                        <div class="mt-2 flex gap-2">
                                            <button type="button" @click="confirming = false" class="btn-ghost flex-1 bg-white dark:bg-slate-900">Keep it</button>
                                            <form method="POST" action="{{ route('student.registrations.cancel', $userRegistration) }}" class="flex-1">
                                                @csrf
                                                <button type="submit" class="btn-danger w-full py-2">Yes, cancel</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @elseif ($remaining <= 0)
                            <button type="button" disabled class="btn w-full cursor-not-allowed bg-slate-200 text-slate-500 dark:text-slate-400">Fully booked</button>
                            <p class="mt-2 text-center text-xs text-slate-400 dark:text-slate-500">All {{ $event->max_participants }} seats are taken.</p>
                        @elseif ($deadlinePassed)
                            <button type="button" disabled class="btn w-full cursor-not-allowed bg-slate-200 text-slate-500 dark:text-slate-400">Registration closed</button>
                            <p class="mt-2 text-center text-xs text-slate-400 dark:text-slate-500">The deadline was {{ $event->registration_deadline->format('M j, Y') }}.</p>
                        @elseif ($event->status !== 'upcoming')
                            <button type="button" disabled class="btn w-full cursor-not-allowed bg-slate-200 text-slate-500 dark:text-slate-400">Registration closed</button>
                            <p class="mt-2 text-center text-xs text-slate-400 dark:text-slate-500">This event is {{ strtolower($event->status) }}.</p>
                        @else
                            <form method="POST" action="{{ route('student.registrations.store', $event) }}">
                                @csrf
                                <button type="submit" class="btn-primary w-full">
                                    Register now — it's free
                                </button>
                            </form>
                            <p class="mt-2 text-center text-xs text-slate-400 dark:text-slate-500">Approval is usually same-day.</p>
                        @endif
                    @endauth
                </div>
            </div>

            <div class="card p-6">
                <h3 class="text-sm font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">Organizer</h3>
                <div class="mt-3 flex items-center gap-3">
                    <span class="gradient-spectrum grid h-10 w-10 place-items-center rounded-xl text-white">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/></svg>
                    </span>
                    <div>
                        <p class="text-sm font-semibold text-slate-900 dark:text-white">Events Committee</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Fiesta 2026 · {{ $event->category?->name ?? 'College' }}</p>
                    </div>
                </div>
            </div>
        </aside>
    </div>
</div>
@endsection

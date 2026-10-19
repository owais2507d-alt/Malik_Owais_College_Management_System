@extends('layouts.app')

@section('title', 'My Registrations')

@section('content')
@include('partials.student-nav')

<div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
    <div>
        <p class="text-sm font-semibold uppercase tracking-widest text-brand-600 dark:text-brand-400">Student panel</p>
        <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-900 dark:text-white sm:text-3xl">My registrations</h1>
        <p class="mt-1 text-slate-500 dark:text-slate-400 dark:text-slate-500">Track approval status and cancel events you can no longer attend.</p>
    </div>

    {{-- Status pills --}}
    @php
        $tabs = [
            '' => ['All', $registrations->total()],
            'pending' => ['Pending', $counts->get('pending', 0)],
            'approved' => ['Approved', $counts->get('approved', 0)],
            'rejected' => ['Rejected', $counts->get('rejected', 0)],
            'cancelled' => ['Cancelled', $counts->get('cancelled', 0)],
        ];
    @endphp
    <div class="mt-6 flex flex-wrap gap-2">
        @foreach ($tabs as $value => $tab)
            @php $active = $status === ($value !== '' ? $value : null); @endphp
            <a href="{{ route('student.registrations.index', array_filter(['status' => $value !== '' ? $value : null], fn ($v) => $v !== null)) }}"
               class="{{ $active ? 'bg-brand-600 text-white shadow-sm' : 'bg-white dark:bg-slate-900 text-slate-600 dark:text-slate-300 ring-1 ring-slate-200 hover:bg-slate-50' }} rounded-full px-4 py-1.5 text-sm font-semibold transition">
                {{ $tab[0] }}
                <span class="{{ $active ? 'text-white/70' : 'text-slate-400 dark:text-slate-500' }} ml-1">{{ $tab[1] }}</span>
            </a>
        @endforeach
    </div>

    @if ($registrations->isEmpty())
        <div class="card mt-6 p-12 text-center">
            <span class="mx-auto grid h-14 w-14 place-items-center rounded-2xl bg-brand-50 text-brand-500">
                <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01"/></svg>
            </span>
            <h2 class="mt-4 text-lg font-semibold text-slate-900 dark:text-white">
                {{ $status ? 'No '.ucfirst($status).' registrations' : 'No registrations yet' }}
            </h2>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400 dark:text-slate-500">
                {{ $status ? 'Try another filter to see your other registrations.' : 'Explore the calendar and claim your first seat.' }}
            </p>
            <a href="{{ route('events.index') }}" class="btn-primary mt-5 inline-flex">Explore events</a>
        </div>
    @else
        <div class="mt-6 space-y-4">
            @foreach ($registrations as $registration)
                @php $event = $registration->event; @endphp
                <div class="card p-5 transition hover:shadow-md" x-data="{ confirming: false }">
                    <div class="flex flex-wrap items-start gap-4">
                        <div class="grid h-14 w-14 shrink-0 place-items-center rounded-xl bg-gradient-to-br from-brand-500 to-fuchsia-500 text-center text-white">
                            <span class="text-lg font-bold leading-none">{{ $event?->event_date->format('j') ?? '?' }}</span>
                            <span class="text-[10px] font-semibold uppercase leading-none">{{ $event?->event_date->format('M') ?? '' }}</span>
                        </div>

                        <div class="min-w-0 flex-1">
                            <div class="flex flex-wrap items-center gap-2">
                                <a href="{{ $event ? route('events.show', $event) : '#' }}" class="truncate font-semibold text-slate-900 dark:text-white hover:text-brand-600 dark:text-brand-400">
                                    {{ $event?->title ?? 'Event no longer available' }}
                                </a>
                                <span class="badge-{{ $registration->status }}">{{ ucfirst($registration->status) }}</span>
                            </div>
                            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400 dark:text-slate-500">
                                @if ($event)
                                    {{ $event->event_date->format('D, M j, Y') }} ·
                                    {{ \Illuminate\Support\Carbon::parse($event->start_time)->format('g:i A') }} ·
                                    {{ $event->venue }} · {{ $event->category?->name }}
                                @endif
                            </p>
                            <p class="mt-0.5 text-xs text-slate-400 dark:text-slate-500">Registered {{ $registration->registered_at->format('M j, Y · g:i A') }}</p>
                        </div>

                        <div class="flex shrink-0 flex-wrap items-center gap-2">
                            @if ($event)
                                <a href="{{ route('events.show', $event) }}" class="btn-ghost text-sm">View event</a>
                            @endif

                            @if ($registration->isCancellable())
                                <button type="button" x-show="!confirming" @click="confirming = true"
                                        class="btn border border-rejected-200 text-sm text-rejected-600 dark:text-red-300 hover:bg-rejected-50">
                                    Cancel
                                </button>
                                <div x-show="confirming" x-cloak x-transition class="flex items-center gap-2 rounded-lg bg-rejected-50 px-3 py-1.5">
                                    <span class="text-xs font-medium text-rejected-600 dark:text-red-300">Sure?</span>
                                    <button type="button" @click="confirming = false" class="text-xs font-semibold text-slate-500 dark:text-slate-400 dark:text-slate-500 hover:text-slate-700 dark:text-slate-200">No</button>
                                    <form method="POST" action="{{ route('student.registrations.cancel', $registration) }}">
                                        @csrf
                                        <button type="submit" class="text-xs font-bold text-rejected-600 dark:text-red-300 hover:underline">Yes, cancel</button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $registrations->links() }}
        </div>
    @endif
</div>
@endsection

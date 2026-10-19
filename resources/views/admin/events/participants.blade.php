@extends('layouts.admin')

@section('page_title', 'Event Participants')

@section('content')
<div class="flex flex-wrap items-start justify-between gap-4">
    <div>
        <a href="{{ route('admin.events.index') }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-slate-500 hover:text-brand-600">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 19-7-7 7-7M19 12H5"/></svg>
            Back to events
        </a>
        <div class="mt-2 flex flex-wrap items-center gap-2">
            <h2 class="text-xl font-bold text-slate-900">{{ $event->title }}</h2>
            <span class="badge-{{ $event->status }}">{{ ucfirst($event->status) }}</span>
        </div>
        <p class="mt-1 text-sm text-slate-500">
            {{ $event->event_date->format('l, M j, Y') }} ·
            {{ \Illuminate\Support\Carbon::parse($event->start_time)->format('g:i A') }}–{{ \Illuminate\Support\Carbon::parse($event->end_time)->format('g:i A') }} ·
            {{ $event->venue }}
        </p>
    </div>

    <a href="{{ route('admin.events.edit', $event) }}" class="btn-secondary">
        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/></svg>
        Edit event
    </a>
</div>

@php
    $percent = $event->max_participants > 0 ? min(100, (int) ceil($taken / $event->max_participants * 100)) : 0;
@endphp
<div class="card mt-5 p-5">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <p class="text-sm font-semibold text-slate-900">Capacity usage</p>
            <p class="text-xs text-slate-500">Seats count pending + approved registrations</p>
        </div>
        <p class="text-sm">
            <span class="text-xl font-bold {{ $taken >= $event->max_participants ? 'text-rejected-600' : 'text-slate-900' }}">{{ $taken }}</span>
            <span class="text-slate-400">/ {{ $event->max_participants }} seats</span>
            @if ($taken >= $event->max_participants)
                <span class="badge-rejected ml-2">Full</span>
            @else
                <span class="badge-approved ml-2">{{ $event->max_participants - $taken }} left</span>
            @endif
        </p>
    </div>
    <div class="mt-3 h-2.5 w-full overflow-hidden rounded-full bg-slate-100">
        <div class="h-full rounded-full {{ $percent >= 100 ? 'bg-rejected-500' : ($percent >= 75 ? 'bg-amber-500' : 'gradient-spectrum') }}" style="width: {{ $percent }}%"></div>
    </div>
</div>

<div class="card mt-5 overflow-hidden">
    <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4">
        <div>
            <h3 class="font-bold text-slate-900">Participants</h3>
            <p class="text-xs text-slate-500">{{ $registrations->total() }} registration{{ $registrations->total() === 1 ? '' : 's' }}</p>
        </div>
    </div>

    @if ($registrations->isEmpty())
        <div class="p-12 text-center text-slate-500">No registrations for this event yet.</div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-xs font-semibold uppercase tracking-wider text-slate-500">
                    <tr>
                        <th class="px-6 py-3.5">#</th>
                        <th class="px-4 py-3.5">Student</th>
                        <th class="px-4 py-3.5">Contact</th>
                        <th class="px-4 py-3.5">Registered</th>
                        <th class="px-4 py-3.5">Status</th>
                        <th class="px-4 py-3.5 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach ($registrations as $registration)
                        <tr class="hover:bg-slate-50/70">
                            <td class="px-6 py-3.5 text-xs font-semibold text-slate-400">{{ $registration->id }}</td>
                            <td class="px-4 py-3.5">
                                <div class="flex items-center gap-3">
                                    <span class="grid h-9 w-9 shrink-0 place-items-center rounded-full bg-brand-100 text-xs font-bold text-brand-700">
                                        {{ strtoupper(substr($registration->user?->name ?? '?', 0, 1)) }}
                                    </span>
                                    <span class="font-semibold text-slate-900">{{ $registration->user?->name ?? 'Deleted user' }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-3.5 text-slate-500">
                                <p class="text-xs">{{ $registration->user?->email }}</p>
                                <p class="text-xs">{{ $registration->user?->phone ?: '—' }}</p>
                            </td>
                            <td class="whitespace-nowrap px-4 py-3.5 text-slate-500">{{ $registration->registered_at->format('M j, Y · g:i A') }}</td>
                            <td class="px-4 py-3.5"><span class="badge-{{ $registration->status }}">{{ ucfirst($registration->status) }}</span></td>
                            <td class="whitespace-nowrap px-4 py-3.5 text-right">
                                @if ($registration->status === 'pending')
                                    <div class="inline-flex gap-1.5">
                                        <form method="POST" action="{{ route('admin.registrations.approve', $registration) }}">
                                            @csrf
                                            <button type="submit" class="rounded-lg bg-approved-600 px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-approved-700">Approve</button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.registrations.reject', $registration) }}">
                                            @csrf
                                            <button type="submit" class="rounded-lg bg-rejected-50 px-3 py-1.5 text-xs font-semibold text-rejected-600 ring-1 ring-rejected-500/25 transition hover:bg-rejected-600 hover:text-white hover:ring-rejected-600">Reject</button>
                                        </form>
                                    </div>
                                @else
                                    <span class="text-xs text-slate-400">—</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

<div class="mt-6">
    {{ $registrations->links() }}
</div>
@endsection

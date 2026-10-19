@extends('layouts.admin')

@section('page_title', 'Dashboard')

@section('content')
<div class="grid grid-cols-2 gap-4 xl:grid-cols-4">
    @foreach ([
        ['label' => 'Total events', 'value' => $stats['events'], 'hint' => 'on the calendar', 'icon' => '<rect width="18" height="18" x="3" y="4" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/>', 'class' => 'bg-brand-50 text-brand-600'],
        ['label' => 'Registered students', 'value' => $stats['students'], 'hint' => 'accounts', 'icon' => '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/>', 'class' => 'bg-sky-50 text-sky-600'],
        ['label' => 'Active registrations', 'value' => $stats['registrations'], 'hint' => 'pending + approved', 'icon' => '<path d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01"/>', 'class' => 'bg-teal-50 text-teal-600'],
        ['label' => 'Pending approvals', 'value' => $stats['pending'], 'hint' => 'needs your review', 'icon' => '<circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/>', 'class' => 'bg-amber-50 text-amber-600'],
    ] as $stat)
        <div class="card px-5 py-5">
            <div class="flex items-start justify-between gap-2">
                <span class="grid h-10 w-10 shrink-0 place-items-center rounded-xl {{ $stat['class'] }}">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">{!! $stat['icon'] !!}</svg>
                </span>
            </div>
            <p class="mt-3 text-2xl font-bold tracking-tight text-slate-900">{{ $stat['value'] }}</p>
            <p class="text-sm font-medium text-slate-600">{{ $stat['label'] }}</p>
            <p class="mt-0.5 text-xs text-slate-400">{{ $stat['hint'] }}</p>
        </div>
    @endforeach
</div>

<div class="mt-6 grid gap-6 xl:grid-cols-3">
    {{-- Upcoming events --}}
    <div class="card overflow-hidden xl:col-span-2">
        <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4">
            <div>
                <h2 class="font-bold text-slate-900">Upcoming events</h2>
                <p class="text-xs text-slate-500">Next events with live seat usage</p>
            </div>
            <a href="{{ route('admin.events.index') }}" class="text-sm font-semibold text-brand-600 hover:text-brand-700">Manage all →</a>
        </div>

        @if ($upcoming->isEmpty())
            <p class="px-6 py-8 text-center text-sm text-slate-500">No upcoming events. Create one to get started.</p>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 text-xs font-semibold uppercase tracking-wider text-slate-500">
                        <tr>
                            <th class="px-6 py-3">Event</th>
                            <th class="px-4 py-3">Date</th>
                            <th class="px-4 py-3">Seats</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($upcoming as $event)
                            @php
                                $taken = $event->active_registrations;
                                $percent = $event->max_participants > 0 ? min(100, (int) ceil($taken / $event->max_participants * 100)) : 0;
                            @endphp
                            <tr class="hover:bg-slate-50/70">
                                <td class="px-6 py-3.5">
                                    <p class="font-semibold text-slate-900">{{ $event->title }}</p>
                                    <p class="text-xs text-slate-500">{{ $event->category?->name }} · {{ $event->venue }}</p>
                                </td>
                                <td class="whitespace-nowrap px-4 py-3.5 text-slate-600">{{ $event->event_date->format('M j, Y') }}</td>
                                <td class="px-4 py-3.5">
                                    <p class="text-xs font-semibold text-slate-700">{{ $taken }}/{{ $event->max_participants }}</p>
                                    <div class="mt-1 h-1.5 w-24 overflow-hidden rounded-full bg-slate-100">
                                        <div class="h-full rounded-full {{ $percent >= 100 ? 'bg-rejected-500' : ($percent >= 75 ? 'bg-amber-500' : 'bg-brand-500') }}" style="width: {{ $percent }}%"></div>
                                    </div>
                                </td>
                                <td class="px-4 py-3.5"><span class="badge-{{ $event->status }}">{{ ucfirst($event->status) }}</span></td>
                                <td class="px-4 py-3.5 text-right">
                                    <a href="{{ route('admin.events.participants', $event) }}" class="text-xs font-semibold text-brand-600 hover:text-brand-700">Participants →</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    {{-- Registration status breakdown --}}
    <div class="card p-6">
        <h2 class="font-bold text-slate-900">Registration status</h2>
        <p class="text-xs text-slate-500">Across all events</p>

        @php
            $totalRegs = max(1, $statusCounts->sum());
            $breakdown = [
                'approved' => ['label' => 'Approved', 'class' => 'bg-approved-500'],
                'pending' => ['label' => 'Pending', 'class' => 'bg-pending-500'],
                'rejected' => ['label' => 'Rejected', 'class' => 'bg-rejected-500'],
                'cancelled' => ['label' => 'Cancelled', 'class' => 'bg-cancelled-500'],
            ];
        @endphp

        <div class="mt-5 flex h-3 w-full overflow-hidden rounded-full bg-slate-100">
            @foreach ($breakdown as $key => $item)
                @php $count = $statusCounts->get($key, 0); @endphp
                @if ($count > 0)
                    <div class="{{ $item['class'] }}" style="width: {{ round($count / $totalRegs * 100) }}%" title="{{ $item['label'] }}: {{ $count }}"></div>
                @endif
            @endforeach
        </div>

        <ul class="mt-5 space-y-3">
            @foreach ($breakdown as $key => $item)
                @php $count = $statusCounts->get($key, 0); @endphp
                <li class="flex items-center justify-between text-sm">
                    <span class="flex items-center gap-2 text-slate-600">
                        <span class="h-2.5 w-2.5 rounded-full {{ $item['class'] }}"></span>
                        {{ $item['label'] }}
                    </span>
                    <span class="font-bold text-slate-900">{{ $count }}</span>
                </li>
            @endforeach
        </ul>

        <a href="{{ route('admin.registrations.index', ['status' => 'pending']) }}" class="btn-primary mt-6 w-full">
            Review pending ({{ $stats['pending'] }})
        </a>
    </div>
</div>

{{-- Recent registrations --}}
<div class="card mt-6 overflow-hidden">
    <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4">
        <h2 class="font-bold text-slate-900">Recent registrations</h2>
        <a href="{{ route('admin.registrations.index') }}" class="text-sm font-semibold text-brand-600 hover:text-brand-700">View all →</a>
    </div>

    @if ($recent->isEmpty())
        <p class="px-6 py-8 text-center text-sm text-slate-500">No registrations yet.</p>
    @else
        <ul class="divide-y divide-slate-100">
            @foreach ($recent as $registration)
                <li class="flex flex-wrap items-center gap-3 px-6 py-3.5 hover:bg-slate-50/70">
                    <span class="grid h-9 w-9 shrink-0 place-items-center rounded-full bg-brand-100 text-xs font-bold text-brand-700">
                        {{ strtoupper(substr($registration->user?->name ?? '?', 0, 1)) }}
                    </span>
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-semibold text-slate-900">{{ $registration->user?->name ?? 'Deleted user' }}</p>
                        <p class="truncate text-xs text-slate-500">→ {{ $registration->event?->title ?? 'Deleted event' }}</p>
                    </div>
                    <span class="badge-{{ $registration->status }}">{{ ucfirst($registration->status) }}</span>
                    <span class="w-24 shrink-0 text-right text-xs text-slate-400">{{ $registration->created_at->diffForHumans() }}</span>
                </li>
            @endforeach
        </ul>
    @endif
</div>
@endsection

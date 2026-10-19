@extends('layouts.admin')

@section('page_title', 'Registration Management')

@section('content')
<div class="flex flex-wrap items-end justify-between gap-4">
    <div class="flex flex-wrap gap-2">
        @php
            $tabs = [
                '' => ['All', $counts->sum()],
                'pending' => ['Pending', $counts->get('pending', 0)],
                'approved' => ['Approved', $counts->get('approved', 0)],
                'rejected' => ['Rejected', $counts->get('rejected', 0)],
                'cancelled' => ['Cancelled', $counts->get('cancelled', 0)],
            ];
        @endphp
        @foreach ($tabs as $value => $tab)
            @php $active = $status === ($value !== '' ? $value : null); @endphp
            <a href="{{ route('admin.registrations.index', array_filter(['status' => $value !== '' ? $value : null], fn ($v) => $v !== null)) }}"
               class="{{ $active ? 'bg-brand-600 text-white shadow-sm' : 'bg-white text-slate-600 ring-1 ring-slate-200 hover:bg-slate-50' }} rounded-full px-4 py-1.5 text-sm font-semibold transition">
                {{ $tab[0] }}
                <span class="{{ $active ? 'text-white/70' : 'text-slate-400' }} ml-1">{{ $tab[1] }}</span>
            </a>
        @endforeach
    </div>
</div>

<form method="GET" action="{{ route('admin.registrations.index') }}" class="card mt-4 flex flex-col gap-3 p-4 sm:flex-row">
    <select name="status" class="input sm:w-44" aria-label="Status">
        <option value="">All statuses</option>
        @foreach (['pending', 'approved', 'rejected', 'cancelled'] as $option)
            <option value="{{ $option }}" @selected($status === $option)>{{ ucfirst($option) }}</option>
        @endforeach
    </select>
    <select name="event" class="input flex-1" aria-label="Event">
        <option value="">All events</option>
        @foreach ($events as $event)
            <option value="{{ $event->id }}" @selected((int) request('event') === $event->id)>
                {{ $event->event_date->format('M j') }} — {{ $event->title }}
            </option>
        @endforeach
    </select>
    <div class="flex gap-2">
        <button type="submit" class="btn-primary">Apply</button>
        @if ($status || request('event'))
            <a href="{{ route('admin.registrations.index') }}" class="btn-ghost">Clear</a>
        @endif
    </div>
</form>

<div class="card mt-5 overflow-hidden" x-data="{ open: false, action: '', mode: '' }">
    @if ($registrations->isEmpty())
        <div class="p-12 text-center">
            <h2 class="text-lg font-semibold text-slate-900">Nothing to review</h2>
            <p class="mt-1 text-sm text-slate-500">No registrations match the current filters.</p>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-xs font-semibold uppercase tracking-wider text-slate-500">
                    <tr>
                        <th class="px-6 py-3.5">Student</th>
                        <th class="px-4 py-3.5">Event</th>
                        <th class="px-4 py-3.5">Registered</th>
                        <th class="px-4 py-3.5">Status</th>
                        <th class="px-4 py-3.5 text-right">Decision</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach ($registrations as $registration)
                        <tr class="hover:bg-slate-50/70">
                            <td class="px-6 py-3.5">
                                <div class="flex items-center gap-3">
                                    <span class="grid h-9 w-9 shrink-0 place-items-center rounded-full bg-brand-100 text-xs font-bold text-brand-700">
                                        {{ strtoupper(substr($registration->user?->name ?? '?', 0, 1)) }}
                                    </span>
                                    <div class="min-w-0">
                                        <p class="truncate font-semibold text-slate-900">{{ $registration->user?->name ?? 'Deleted user' }}</p>
                                        <p class="truncate text-xs text-slate-500">{{ $registration->user?->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3.5">
                                @if ($registration->event)
                                    <a href="{{ route('events.show', $registration->event) }}" class="font-medium text-slate-800 hover:text-brand-600" target="_blank" rel="noopener">
                                        {{ $registration->event->title }}
                                    </a>
                                    <p class="text-xs text-slate-500">{{ $registration->event->event_date->format('M j, Y') }} · {{ $registration->event->venue }}</p>
                                @else
                                    <span class="text-slate-400">Deleted event</span>
                                @endif
                            </td>
                            <td class="whitespace-nowrap px-4 py-3.5 text-slate-500">{{ $registration->registered_at->format('M j, Y') }}</td>
                            <td class="px-4 py-3.5"><span class="badge-{{ $registration->status }}">{{ ucfirst($registration->status) }}</span></td>
                            <td class="whitespace-nowrap px-4 py-3.5 text-right">
                                @if (in_array($registration->status, ['pending', 'approved', 'rejected'], true))
                                    <div class="inline-flex items-center gap-1.5">
                                        @if ($registration->status !== 'approved')
                                            <button type="button"
                                                    @click="action = '{{ route('admin.registrations.approve', $registration) }}'; mode = 'approve'; open = true"
                                                    class="inline-flex items-center gap-1 rounded-lg bg-approved-50 px-3 py-1.5 text-xs font-semibold text-approved-600 ring-1 ring-approved-500/25 transition hover:bg-approved-500 hover:text-white hover:ring-approved-500">
                                                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                                                Approve
                                            </button>
                                        @endif
                                        @if ($registration->status !== 'rejected')
                                            <button type="button"
                                                    @click="action = '{{ route('admin.registrations.reject', $registration) }}'; mode = 'reject'; open = true"
                                                    class="inline-flex items-center gap-1 rounded-lg bg-rejected-50 px-3 py-1.5 text-xs font-semibold text-rejected-600 ring-1 ring-rejected-500/25 transition hover:bg-rejected-500 hover:text-white hover:ring-rejected-500">
                                                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18M6 6l12 12"/></svg>
                                                Reject
                                            </button>
                                        @endif
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

    {{-- Decision confirm --}}
    <div x-show="open" x-cloak x-transition.opacity class="fixed inset-0 z-50 grid place-items-center bg-slate-900/50 p-4 backdrop-blur-sm">
        <div x-show="open" x-transition class="w-full max-w-sm rounded-2xl bg-white p-6 text-center shadow-2xl">
            <span class="mx-auto grid h-12 w-12 place-items-center rounded-full"
                  :class="mode === 'approve' ? 'bg-approved-50 text-approved-600' : 'bg-rejected-50 text-rejected-600'">
                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 6 9 17l-5-5" x-show="mode === 'approve'"></path>
                    <path d="M18 6 6 18M6 6l12 12" x-show="mode === 'reject'"></path>
                </svg>
            </span>
            <h3 class="mt-4 font-bold text-slate-900" x-text="mode === 'approve' ? 'Approve this registration?' : 'Reject this registration?'"></h3>
            <p class="mt-1 text-sm text-slate-500">The student's status will update immediately.</p>
            <form method="POST" :action="action" class="mt-5 flex gap-2">
                @csrf
                <button type="submit" class="flex-1 rounded-lg px-4 py-2.5 text-sm font-semibold text-white transition disabled:opacity-60"
                        :class="mode === 'approve' ? 'bg-approved-600 hover:bg-approved-700' : 'bg-rejected-600 hover:bg-rejected-700'">
                    <span x-text="mode === 'approve' ? 'Approve' : 'Reject'"></span>
                </button>
                <button type="button" @click="open = false" class="btn-secondary flex-1">Cancel</button>
            </form>
        </div>
    </div>
</div>

<div class="mt-6">
    {{ $registrations->links() }}
</div>
@endsection

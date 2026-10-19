@extends('layouts.admin')

@section('page_title', 'Manage Events')

@section('content')
<div class="flex flex-wrap items-end justify-between gap-4">
    <div>
        <p class="text-sm text-slate-500">{{ $events->total() }} event{{ $events->total() === 1 ? '' : 's' }} on record</p>
    </div>
    <a href="{{ route('admin.events.create') }}" class="btn-primary sm:hidden">
        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>
        New event
    </a>
</div>

<form method="GET" action="{{ route('admin.events.index') }}" class="card mt-4 flex flex-col gap-3 p-4 sm:flex-row">
    <input type="search" name="q" value="{{ $q }}" placeholder="Search title or venue..." class="input flex-1" aria-label="Search events">
    <select name="status" class="input sm:w-44" aria-label="Filter status">
        <option value="">All statuses</option>
        @foreach (['upcoming', 'ongoing', 'completed'] as $option)
            <option value="{{ $option }}" @selected($status === $option)>{{ ucfirst($option) }}</option>
        @endforeach
    </select>
    <div class="flex gap-2">
        <button type="submit" class="btn-primary">Filter</button>
        @if ($q || $status)
            <a href="{{ route('admin.events.index') }}" class="btn-ghost">Clear</a>
        @endif
    </div>
</form>

<div class="card mt-5 overflow-hidden" x-data="{ open: false, action: '' }">
    @if ($events->isEmpty())
        <div class="p-12 text-center">
            <h2 class="text-lg font-semibold text-slate-900">No events found</h2>
            <p class="mt-1 text-sm text-slate-500">Try different filters, or create the first event.</p>
            <a href="{{ route('admin.events.create') }}" class="btn-primary mt-5 inline-flex">Create event</a>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-xs font-semibold uppercase tracking-wider text-slate-500">
                    <tr>
                        <th class="px-6 py-3.5">Event</th>
                        <th class="px-4 py-3.5">Category</th>
                        <th class="px-4 py-3.5">Date &amp; deadline</th>
                        <th class="px-4 py-3.5">Seats</th>
                        <th class="px-4 py-3.5">Status</th>
                        <th class="px-4 py-3.5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach ($events as $event)
                        @php
                            $taken = $event->active_registrations;
                            $percent = $event->max_participants > 0 ? min(100, (int) ceil($taken / $event->max_participants * 100)) : 0;
                        @endphp
                        <tr class="hover:bg-slate-50/70">
                            <td class="px-6 py-3.5">
                                <div class="flex items-center gap-3">
                                    @if ($event->imageUrl())
                                        <img src="{{ $event->imageUrl() }}" alt="" class="h-11 w-11 shrink-0 rounded-lg object-cover ring-1 ring-slate-200">
                                    @else
                                        <span class="gradient-spectrum grid h-11 w-11 shrink-0 place-items-center rounded-lg text-white">
                                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                                        </span>
                                    @endif
                                    <div class="min-w-0">
                                        <p class="max-w-xs truncate font-semibold text-slate-900">{{ $event->title }}</p>
                                        <p class="max-w-xs truncate text-xs text-slate-500">{{ $event->venue }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3.5 text-slate-600">{{ $event->category?->name ?? '—' }}</td>
                            <td class="whitespace-nowrap px-4 py-3.5">
                                <p class="font-medium text-slate-700">{{ $event->event_date->format('M j, Y') }}</p>
                                <p class="text-xs {{ $event->registration_deadline->isPast() ? 'text-rejected-500' : 'text-slate-400' }}">
                                    deadline {{ $event->registration_deadline->format('M j') }}{{ $event->registration_deadline->isPast() ? ' (passed)' : '' }}
                                </p>
                            </td>
                            <td class="px-4 py-3.5">
                                <p class="text-xs font-semibold {{ $taken >= $event->max_participants ? 'text-rejected-600' : 'text-slate-700' }}">{{ $taken }}/{{ $event->max_participants }}</p>
                                <div class="mt-1 h-1.5 w-20 overflow-hidden rounded-full bg-slate-100">
                                    <div class="h-full rounded-full {{ $percent >= 100 ? 'bg-rejected-500' : ($percent >= 75 ? 'bg-amber-500' : 'bg-brand-500') }}" style="width: {{ $percent }}%"></div>
                                </div>
                            </td>
                            <td class="px-4 py-3.5"><span class="badge-{{ $event->status }}">{{ ucfirst($event->status) }}</span></td>
                            <td class="whitespace-nowrap px-4 py-3.5 text-right">
                                <div class="inline-flex items-center gap-1">
                                    <a href="{{ route('admin.events.participants', $event) }}" class="grid h-8 w-8 place-items-center rounded-lg text-slate-500 transition hover:bg-brand-50 hover:text-brand-600" title="Participants">
                                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                                    </a>
                                    <a href="{{ route('admin.events.edit', $event) }}" class="grid h-8 w-8 place-items-center rounded-lg text-slate-500 transition hover:bg-brand-50 hover:text-brand-600" title="Edit">
                                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/></svg>
                                    </a>
                                    <button type="button" @click="action = '{{ route('admin.events.destroy', $event) }}'; open = true"
                                            class="grid h-8 w-8 place-items-center rounded-lg text-slate-500 transition hover:bg-rejected-50 hover:text-rejected-600" title="Delete">
                                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    {{-- Delete confirm --}}
    <div x-show="open" x-cloak x-transition.opacity class="fixed inset-0 z-50 grid place-items-center bg-slate-900/50 p-4 backdrop-blur-sm" role="dialog">
        <div x-show="open" x-transition class="w-full max-w-sm rounded-2xl bg-white p-6 shadow-2xl">
            <span class="grid h-11 w-11 place-items-center rounded-full bg-rejected-50 text-rejected-600">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2M10 11v6M14 11v6"/></svg>
            </span>
            <h3 class="mt-4 font-bold text-slate-900">Delete this event?</h3>
            <p class="mt-1 text-sm text-slate-500">Students will no longer see it. This action cannot be undone.</p>
            <form method="POST" :action="action" class="mt-5 flex gap-2">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-danger flex-1">Delete event</button>
                <button type="button" @click="open = false" class="btn-secondary flex-1">Cancel</button>
            </form>
        </div>
    </div>
</div>

<div class="mt-6">
    {{ $events->links() }}
</div>
@endsection

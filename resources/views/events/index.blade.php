@extends('layouts.app')

@section('title', 'Explore Events')

@section('content')
<div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8 lg:py-14">
    <div class="max-w-2xl">
        <p class="text-sm font-semibold uppercase tracking-widest text-brand-600 dark:text-brand-400">Fiesta 2026</p>
        <h1 class="mt-1.5 text-3xl font-bold tracking-tight text-slate-900 dark:text-white sm:text-4xl">Explore events</h1>
        <p class="mt-2 text-slate-500 dark:text-slate-400 dark:text-slate-500">Search the full calendar, filter by category, and register before deadlines close.</p>
    </div>

    {{-- Filters --}}
    <form method="GET" action="{{ route('events.index') }}" class="card mt-8 flex flex-col gap-3 p-4 sm:flex-row sm:items-center">
        <div class="relative flex-1">
            <svg class="pointer-events-none absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400 dark:text-slate-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
            <input type="search" name="q" value="{{ $q }}" placeholder="Search by title, description or venue..."
                   class="input pl-10" aria-label="Search events">
        </div>

        <select name="category" class="input sm:w-52" aria-label="Filter by category" onchange="this.form.submit()">
            <option value="">All categories</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" @selected((int) $categoryId === $category->id)>
                    {{ $category->name }} ({{ $category->events_count }})
                </option>
            @endforeach
        </select>

        <div class="flex gap-2">
            <button type="submit" class="btn-primary">Search</button>
            @if ($q || $categoryId || $status)
                <a href="{{ route('events.index') }}" class="btn-ghost">Clear</a>
            @endif
        </div>
    </form>

    {{-- Status tabs --}}
    <div class="mt-6 flex flex-wrap gap-2">
        @php
            $tabs = [
                '' => 'All',
                'upcoming' => 'Upcoming',
                'ongoing' => 'Ongoing',
                'completed' => 'Completed',
            ];
        @endphp
        @foreach ($tabs as $value => $label)
            @php
                $href = route('events.index', array_filter([
                    'status' => $value !== '' ? $value : null,
                    'q' => $q !== '' ? $q : null,
                    'category' => $categoryId > 0 ? $categoryId : null,
                ], fn ($item) => $item !== null));
                $active = $status === ($value !== '' ? $value : null);
            @endphp
            <a href="{{ $href }}"
               class="{{ $active ? 'bg-brand-600 text-white shadow-sm' : 'bg-white dark:bg-slate-900 text-slate-600 dark:text-slate-300 ring-1 ring-slate-200 hover:bg-slate-50' }} rounded-full px-4 py-1.5 text-sm font-semibold transition">
                {{ $label }}
            </a>
        @endforeach
    </div>

    {{-- Results --}}
    @if ($events->isEmpty())
        <div class="card mt-8 p-12 text-center">
            <span class="mx-auto grid h-14 w-14 place-items-center rounded-2xl bg-slate-100 dark:bg-slate-800 text-slate-400 dark:text-slate-500">
                <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
            </span>
            <h2 class="mt-4 text-lg font-semibold text-slate-900 dark:text-white">No events found</h2>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400 dark:text-slate-500">Try different search terms or clear your filters.</p>
            <a href="{{ route('events.index') }}" class="btn-secondary mt-5 inline-flex">Clear all filters</a>
        </div>
    @else
        <div class="mt-6 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($events as $event)
                @include('partials.event-card', ['event' => $event])
            @endforeach
        </div>

        <div class="mt-10">
            {{ $events->links() }}
        </div>
    @endif
</div>
@endsection

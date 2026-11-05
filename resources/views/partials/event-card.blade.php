@php
    $gradients = [
        'from-blue-700 via-blue-600 to-blue-500',
        'from-sky-600 via-sky-500 to-blue-600',
        'from-blue-800 via-blue-700 to-blue-600',
        'from-sky-700 via-sky-600 to-blue-500',
        'from-blue-600 via-blue-500 to-sky-400',
        'from-blue-900 via-blue-700 to-sky-500',
    ];
    $gradient = $gradients[($event->category_id ?? 1) % count($gradients)] ?? $gradients[0];
    $taken = $event->active_registrations ?? $event->seatsTaken();
    $remaining = max(0, $event->max_participants - $taken);
    $deadlinePassed = $event->registration_deadline->isPast();
    $percent = $event->max_participants > 0 ? min(100, round($taken / $event->max_participants * 100)) : 0;
    $barColor = $percent >= 100 ? 'bg-red-500' : ($percent >= 75 ? 'bg-amber-500' : 'bg-brand-500');
@endphp

<a href="{{ route('events.show', $event) }}"
   class="card group flex flex-col overflow-hidden transition duration-300 hover:-translate-y-1.5 hover:shadow-xl hover:shadow-brand-900/10 dark:hover:shadow-black/50">
    {{-- Image box --}}
    <div class="relative h-48 overflow-hidden bg-gradient-to-br {{ $gradient }}">
        @if ($event->image)
            <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($event->image) }}"
                 alt="{{ $event->title }}"
                 loading="lazy"
                 decoding="async"
                 class="h-full w-full object-cover transition duration-700 ease-out group-hover:scale-110">
            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/5 to-black/25"></div>
        @else
            <div class="absolute -right-10 -top-10 h-40 w-40 rounded-full bg-white/25 blur-2xl"></div>
            <div class="absolute -bottom-12 -left-8 h-36 w-36 rounded-full bg-white/15 blur-2xl"></div>
            <div class="absolute inset-0 bg-black/10"></div>
        @endif

        {{-- Category + status pills --}}
        <div class="absolute left-3.5 top-3.5">
            <span class="badge bg-white/95 text-slate-800 shadow-sm backdrop-blur">{{ $event->category?->name ?? 'Event' }}</span>
        </div>
        <div class="absolute right-3.5 top-3.5">
            <span class="badge-{{ $event->status }} bg-white/95 shadow-sm backdrop-blur">{{ ucfirst($event->status) }}</span>
        </div>

        {{-- Premium calendar chip --}}
        <div class="absolute bottom-3.5 left-3.5 flex items-center gap-3 rounded-2xl bg-white/95 px-3.5 py-2.5 shadow-lg backdrop-blur">
            <span class="text-center">
                <span class="block text-xl font-black leading-none text-brand-700">{{ $event->event_date->format('d') }}</span>
                <span class="mt-0.5 block text-[10px] font-bold uppercase tracking-widest text-slate-500">{{ $event->event_date->format('M') }}</span>
            </span>
            <span class="h-9 w-px bg-slate-200"></span>
            <span class="text-xs font-semibold leading-tight text-slate-600">
                {{ $event->event_date->format('D') }}
                <span class="mt-0.5 block text-slate-400">{{ \Illuminate\Support\Carbon::parse($event->start_time)->format('g:i A') }}</span>
            </span>
        </div>
    </div>

    {{-- Body --}}
    <div class="flex flex-1 flex-col p-5">
        <h3 class="line-clamp-2 text-base font-bold leading-snug text-slate-900 transition group-hover:text-brand-600 dark:text-white dark:group-hover:text-brand-400">
            {{ $event->title }}
        </h3>

        <p class="mt-2.5 flex items-center gap-2 text-sm text-slate-500 dark:text-slate-400">
            <svg class="h-4 w-4 shrink-0 text-brand-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
            <span class="truncate">{{ $event->venue }}</span>
        </p>

        {{-- Capacity bar --}}
        <div class="mt-4">
            <div class="mb-1.5 flex items-center justify-between text-xs font-semibold">
                <span class="text-slate-500 dark:text-slate-400">{{ $taken }}/{{ $event->max_participants }} seats</span>
                <span class="{{ $percent >= 100 ? 'text-red-500' : ($percent >= 75 ? 'text-amber-500' : 'text-brand-600 dark:text-brand-400') }}">{{ $percent }}% full</span>
            </div>
            <div class="h-2 overflow-hidden rounded-full bg-slate-100 dark:bg-slate-800">
                <div class="h-full rounded-full {{ $barColor }} transition-all duration-700" style="width: {{ $percent }}%"></div>
            </div>
        </div>

        {{-- Footer --}}
        <div class="mt-auto flex items-center justify-between gap-2 border-t border-slate-100 pt-4 text-xs dark:border-slate-800">
            @if ($remaining <= 0)
                <span class="badge-rejected">Fully booked</span>
            @elseif ($deadlinePassed && $event->status === 'upcoming')
                <span class="badge-pending">Registration closed</span>
            @else
                <span class="badge-approved">{{ $remaining }} seat{{ $remaining === 1 ? '' : 's' }} left</span>
            @endif
            <span class="font-bold text-brand-600 transition group-hover:translate-x-1 dark:text-brand-400">Details →</span>
        </div>
    </div>
</a>

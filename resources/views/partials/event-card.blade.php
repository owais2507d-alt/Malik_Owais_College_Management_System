@php
    $gradients = [
        'from-violet-600 via-fuchsia-500 to-rose-500',
        'from-sky-500 via-indigo-500 to-violet-600',
        'from-teal-500 via-emerald-500 to-lime-500',
        'from-amber-500 via-orange-500 to-rose-500',
        'from-fuchsia-600 via-pink-500 to-rose-500',
        'from-indigo-600 via-blue-500 to-cyan-500',
    ];
    $gradient = $gradients[($event->category_id ?? 1) % count($gradients)] ?? $gradients[0];
    $taken = $event->active_registrations ?? $event->seatsTaken();
    $remaining = max(0, $event->max_participants - $taken);
    $deadlinePassed = $event->registration_deadline->isPast();
@endphp

<a href="{{ route('events.show', $event) }}"
   class="card group flex flex-col overflow-hidden transition duration-200 hover:-translate-y-1 hover:shadow-lg hover:shadow-slate-300/60">
    <div class="relative h-36 overflow-hidden bg-gradient-to-br {{ $gradient }}">
        <div class="absolute -right-8 -top-8 h-32 w-32 rounded-full bg-white/20 blur-2xl"></div>
        <div class="absolute inset-0 bg-black/10"></div>

        <div class="absolute left-3 top-3 flex items-center gap-2">
            <span class="badge bg-white/90 text-slate-800 backdrop-blur">{{ $event->category?->name ?? 'Event' }}</span>
        </div>

        <div class="absolute right-3 top-3">
            <span class="badge-{{ $event->status }} bg-white/90 backdrop-blur">{{ ucfirst($event->status) }}</span>
        </div>

        <div class="absolute bottom-3 left-4 text-white">
            <p class="text-xs font-medium uppercase tracking-widest text-white/80">{{ $event->event_date->format('D, M j') }}</p>
        </div>
    </div>

    <div class="flex flex-1 flex-col p-4">
        <h3 class="line-clamp-1 font-semibold text-slate-900 transition group-hover:text-brand-600">
            {{ $event->title }}
        </h3>

        <div class="mt-2 space-y-1.5 text-sm text-slate-500">
            <p class="flex items-center gap-2">
                <svg class="h-4 w-4 shrink-0 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                {{ \Illuminate\Support\Carbon::parse($event->start_time)->format('g:i A') }} – {{ \Illuminate\Support\Carbon::parse($event->end_time)->format('g:i A') }}
            </p>
            <p class="flex items-center gap-2">
                <svg class="h-4 w-4 shrink-0 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                {{ $event->venue }}
            </p>
        </div>

        <div class="mt-auto flex items-center justify-between gap-2 border-t border-slate-100 pt-3 text-xs">
            @if ($remaining <= 0)
                <span class="badge-rejected">Fully booked</span>
            @elseif ($deadlinePassed && $event->status === 'upcoming')
                <span class="badge-pending">Registration closed</span>
            @else
                <span class="badge-approved">{{ $remaining }} seat{{ $remaining === 1 ? '' : 's' }} left</span>
            @endif
            <span class="font-semibold text-brand-600 transition group-hover:translate-x-0.5">Details →</span>
        </div>
    </div>
</a>

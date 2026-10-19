@extends('layouts.admin')

@section('page_title', 'Messages')

@section('content')
<div class="flex flex-wrap items-end justify-between gap-4">
    <p class="text-sm text-slate-500">{{ $messages->total() }} message{{ $messages->total() === 1 ? '' : 's' }} from the contact form</p>

    @php $unread = \App\Models\ContactMessage::unread()->count(); @endphp
    @if ($unread > 0)
        <form method="POST" action="{{ route('admin.messages.read-all') }}">
            @csrf
            <button type="submit" class="btn-secondary">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                Mark all read ({{ $unread }})
            </button>
        </form>
    @endif
</div>

<div class="card mt-5 overflow-hidden" x-data="{ open: null }">
    @if ($messages->isEmpty())
        <div class="p-12 text-center">
            <span class="mx-auto grid h-12 w-12 place-items-center rounded-full bg-slate-100 text-slate-400">
                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
            </span>
            <h2 class="mt-4 text-lg font-semibold text-slate-900">No messages yet</h2>
            <p class="mt-1 text-sm text-slate-500">Messages sent from the public contact page will appear here.</p>
        </div>
    @else
        <ul class="divide-y divide-slate-100">
            @foreach ($messages as $message)
                <li class="group flex flex-col gap-3 px-6 py-4 transition hover:bg-slate-50/70 sm:flex-row sm:items-start {{ $message->is_read ? '' : 'bg-brand-50/40' }}">
                    <div class="flex min-w-0 flex-1 items-start gap-3">
                        <span class="grid h-10 w-10 shrink-0 place-items-center rounded-full {{ $message->is_read ? 'bg-slate-100 text-slate-500' : 'bg-brand-100 text-brand-700' }} text-sm font-bold">
                            {{ strtoupper(substr($message->name, 0, 1)) }}
                        </span>
                        <div class="min-w-0">
                            <div class="flex flex-wrap items-center gap-2">
                                <p class="truncate font-semibold {{ $message->is_read ? 'text-slate-700' : 'text-slate-900' }}">{{ $message->name }}</p>
                                @unless ($message->is_read)
                                    <span class="badge-upcoming">New</span>
                                @endunless
                                @if ($message->subject)
                                    <span class="truncate text-sm text-slate-500">· {{ $message->subject }}</span>
                                @endif
                            </div>
                            <p class="mt-0.5 truncate text-xs text-slate-500">{{ $message->email }}</p>
                            <p class="mt-1.5 text-sm text-slate-600">{{ \Illuminate\Support\Str::words($message->message, 28, '…') }}</p>
                            <p class="mt-1.5 text-xs text-slate-400">{{ $message->created_at->format('M j, Y · g:i A') }}</p>
                        </div>
                    </div>

                    <div class="flex shrink-0 items-center gap-1.5">
                        <button type="button" @click="open = open === {{ $message->id }} ? null : {{ $message->id }}"
                                class="rounded-lg px-3 py-1.5 text-xs font-semibold text-brand-600 ring-1 ring-brand-500/25 transition hover:bg-brand-600 hover:text-white hover:ring-brand-600">
                            <span x-text="open === {{ $message->id }} ? 'Close' : 'Read'"></span>
                        </button>
                        @unless ($message->is_read)
                            <form method="POST" action="{{ route('admin.messages.read', $message) }}">
                                @csrf
                                <button type="submit" class="rounded-lg px-3 py-1.5 text-xs font-semibold text-slate-500 ring-1 ring-slate-300 transition hover:bg-slate-100" title="Mark as read">
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                                </button>
                            </form>
                        @endunless
                        <form method="POST" action="{{ route('admin.messages.destroy', $message) }}"
                              onsubmit="return confirm('Delete this message from {{ $message->name }}?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="rounded-lg px-3 py-1.5 text-xs font-semibold text-rejected-600 ring-1 ring-rejected-500/25 transition hover:bg-rejected-600 hover:text-white hover:ring-rejected-600" title="Delete">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                            </button>
                        </form>
                    </div>

                    <div x-show="open === {{ $message->id }}" x-cloak x-transition class="rounded-xl bg-slate-50 p-4 text-sm leading-relaxed text-slate-700 sm:mx-14 whitespace-pre-line dark:bg-slate-800 dark:text-slate-300">{{ $message->message }}</div>
                </li>
            @endforeach
        </ul>
    @endif
</div>

<div class="mt-6">
    {{ $messages->links() }}
</div>
@endsection

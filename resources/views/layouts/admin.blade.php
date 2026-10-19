<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('page_title', 'Admin') · {{ config('app.name') }}</title>
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 text-slate-800 antialiased" x-data="{ sidebar: false }">

{{-- Sidebar --}}
<aside class="sidebar-scroll fixed inset-y-0 left-0 z-50 flex w-64 flex-col overflow-y-auto bg-sidebar text-white transition-transform duration-200 lg:translate-x-0"
       :class="sidebar ? 'translate-x-0' : '-translate-x-full'">

    <div class="gradient-spectrum sticky top-0 z-10 flex h-16 items-center gap-2.5 bg-transparent px-5">
        <span class="grid h-9 w-9 place-items-center rounded-xl bg-white/20 backdrop-blur">
            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/></svg>
        </span>
        <div class="leading-tight">
            <p class="text-sm font-bold">Fiesta <span class="text-white/70">2026</span></p>
            <p class="text-[11px] text-white/60">Admin Panel</p>
        </div>
        <button type="button" @click="sidebar = false" class="ml-auto text-white/70 hover:text-white lg:hidden" aria-label="Close sidebar">
            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18M6 6l12 12"/></svg>
        </button>
    </div>

    <nav class="mt-3 flex-1 space-y-6 px-3 pb-6">
        <div>
            <p class="px-3 text-[11px] font-semibold uppercase tracking-widest text-white/40">Overview</p>
            <a href="{{ route('admin.dashboard') }}"
               class="mt-1.5 flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition {{ request()->routeIs('admin.dashboard') ? 'bg-sidebar-active text-white shadow-sm' : 'text-white/60 hover:bg-sidebar-light hover:text-white' }}">
                <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect width="7" height="9" x="3" y="3" rx="1"/><rect width="7" height="5" x="14" y="3" rx="1"/><rect width="7" height="9" x="14" y="12" rx="1"/><rect width="7" height="5" x="3" y="16" rx="1"/></svg>
                Dashboard
            </a>
        </div>

        <div>
            <p class="px-3 text-[11px] font-semibold uppercase tracking-widest text-white/40">Manage</p>
            <div class="mt-1.5 space-y-1">
                <a href="{{ route('admin.events.index') }}"
                   class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition {{ request()->routeIs('admin.events.*') && ! request()->routeIs('admin.events.participants') ? 'bg-sidebar-active text-white shadow-sm' : 'text-white/60 hover:bg-sidebar-light hover:text-white' }}">
                    <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                    Events
                </a>
                <a href="{{ route('admin.categories.index') }}"
                   class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition {{ request()->routeIs('admin.categories.*') ? 'bg-sidebar-active text-white shadow-sm' : 'text-white/60 hover:bg-sidebar-light hover:text-white' }}">
                    <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 7V4h16v3M9 20h6M12 4v16"/></svg>
                    Categories
                </a>
                <a href="{{ route('admin.registrations.index') }}"
                   class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition {{ request()->routeIs('admin.registrations.*') ? 'bg-sidebar-active text-white shadow-sm' : 'text-white/60 hover:bg-sidebar-light hover:text-white' }}">
                    <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    Registrations
                    @php $pendingCount = \App\Models\EventRegistration::pending()->count(); @endphp
                    @if ($pendingCount > 0)
                        <span class="ml-auto rounded-full bg-amber-500 px-2 py-0.5 text-[11px] font-bold text-white">{{ $pendingCount }}</span>
                    @endif
                </a>
            </div>
        </div>

        <div>
            <p class="px-3 text-[11px] font-semibold uppercase tracking-widest text-white/40">Site</p>
            <a href="{{ route('home') }}" target="_blank" rel="noopener"
               class="mt-1.5 flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-white/60 transition hover:bg-sidebar-light hover:text-white">
                <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h6v6M10 14 21 3M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/></svg>
                View public site
            </a>
        </div>
    </nav>

    <div class="sticky bottom-0 border-t border-white/10 bg-sidebar/95 p-4 backdrop-blur">
        <div class="flex items-center gap-3">
            <span class="grid h-9 w-9 shrink-0 place-items-center rounded-full bg-brand-500 text-sm font-bold text-white">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </span>
            <div class="min-w-0">
                <p class="truncate text-sm font-semibold">{{ auth()->user()->name }}</p>
                <p class="truncate text-xs text-white/50">{{ auth()->user()->email }}</p>
            </div>
            <form method="POST" action="{{ route('logout') }}" class="ml-auto">
                @csrf
                <button type="submit" class="grid h-8 w-8 place-items-center rounded-lg text-white/50 transition hover:bg-white/10 hover:text-white" title="Sign out" aria-label="Sign out">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><path d="m16 17 5-5-5-5M21 12H9"/></svg>
                </button>
            </form>
        </div>
    </div>
</aside>

{{-- Mobile backdrop --}}
<div x-show="sidebar" x-cloak x-transition.opacity class="fixed inset-0 z-40 bg-slate-900/50 backdrop-blur-sm lg:hidden" @click="sidebar = false"></div>

<div class="lg:pl-64">
    {{-- Topbar --}}
    <header class="sticky top-0 z-30 flex h-16 items-center gap-4 border-b border-slate-200 bg-white/85 px-4 backdrop-blur sm:px-6">
        <button type="button" @click="sidebar = !sidebar" class="grid h-10 w-10 place-items-center rounded-lg text-slate-600 hover:bg-slate-100 lg:hidden" aria-label="Open sidebar">
            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>

        <div class="min-w-0">
            <h1 class="truncate text-lg font-bold text-slate-900">@yield('page_title', 'Dashboard')</h1>
        </div>

        <div class="ml-auto flex items-center gap-3">
            <a href="{{ route('admin.events.create') }}" class="btn-primary hidden sm:inline-flex">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>
                New event
            </a>
            <div class="hidden h-8 w-px bg-slate-200 sm:block"></div>
            <span class="grid h-9 w-9 place-items-center rounded-full bg-gradient-to-br from-brand-500 to-fuchsia-500 text-sm font-bold text-white">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </span>
        </div>
    </header>

    <main class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
        @include('partials.toast')
        @yield('content')
    </main>
</div>

@stack('scripts')
</body>
</html>

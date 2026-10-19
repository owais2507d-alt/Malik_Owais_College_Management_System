<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name'))</title>
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    <script>
        (function () {
            var t = localStorage.getItem('theme');
            if (t === 'dark' || (!t && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            }
        })();
    </script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex min-h-screen flex-col bg-slate-50 text-slate-800">

<nav class="sticky top-0 z-40 border-b border-slate-200/70 bg-white/85 backdrop-blur dark:border-slate-800/80 dark:bg-slate-950/85" x-data="{ mobile: false, user: false }">
    <div class="mx-auto flex h-16 max-w-7xl items-center justify-between gap-4 px-4 sm:px-6 lg:px-8">
        <a href="{{ route('home') }}" class="flex shrink-0 items-center gap-2.5 font-bold tracking-tight text-slate-900 dark:text-white">
            <span class="gradient-spectrum grid h-9 w-9 place-items-center rounded-xl text-white shadow-sm transition hover:scale-105">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/></svg>
            </span>
            <span class="hidden sm:block">Fiesta <span class="text-brand-600 dark:text-brand-400">2026</span></span>
        </a>

        <div class="hidden items-center gap-1 md:flex">
            <a href="{{ route('home') }}" class="rounded-lg px-3 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-100 hover:text-slate-900 dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-white">Home</a>
            <a href="{{ route('events.index') }}" class="rounded-lg px-3 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-100 hover:text-slate-900 dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-white">Events</a>
            <a href="{{ route('contact.show') }}" class="rounded-lg px-3 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-100 hover:text-slate-900 dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-white">Contact</a>
            @auth
                @if (auth()->user()->isAdmin() && Route::has('admin.dashboard'))
                    <a href="{{ route('admin.dashboard') }}" class="rounded-lg px-3 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-100 hover:text-slate-900 dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-white">Admin Panel</a>
                @elseif (auth()->user()->isStudent() && Route::has('student.dashboard'))
                    <a href="{{ route('student.dashboard') }}" class="rounded-lg px-3 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-100 hover:text-slate-900 dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-white">My Dashboard</a>
                @endif
            @endauth
        </div>

        <div class="flex items-center gap-2">
            @include('partials.theme-toggle')
            @guest
                <a href="{{ route('login') }}" class="btn-ghost hidden sm:inline-flex">Sign in</a>
                <a href="{{ route('register') }}" class="btn-primary hidden sm:inline-flex">Get started</a>
            @else
                <div class="relative hidden md:block">
                    <button type="button" @click="user = !user" @click.outside="user = false"
                            class="flex items-center gap-2.5 rounded-full py-1 pl-1 pr-3 ring-1 ring-slate-200 transition hover:bg-slate-50 dark:ring-slate-700 dark:hover:bg-slate-900">
                        <span class="grid h-8 w-8 place-items-center rounded-full bg-brand-600 text-sm font-bold text-white">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </span>
                        <span class="text-left leading-tight">
                            <span class="block text-sm font-semibold text-slate-800 dark:text-slate-100">{{ \Illuminate\Support\Str::words(auth()->user()->name, 1, '') }}</span>
                            <span class="block text-[11px] capitalize text-slate-500 dark:text-slate-400">{{ auth()->user()->role }}</span>
                        </span>
                        <svg class="h-4 w-4 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                    </button>

                    <div x-show="user" x-cloak x-transition.origin.top.right
                         class="absolute right-0 mt-2 w-52 rounded-xl border border-slate-200 bg-white p-1.5 shadow-lg shadow-slate-900/10 dark:border-slate-800 dark:bg-slate-900 dark:shadow-black/40">
                        <div class="border-b border-slate-100 px-3 py-2 dark:border-slate-800">
                            <p class="truncate text-sm font-semibold text-slate-900 dark:text-slate-100">{{ auth()->user()->name }}</p>
                            <p class="truncate text-xs text-slate-500 dark:text-slate-400">{{ auth()->user()->email }}</p>
                        </div>
                        @if (auth()->user()->isAdmin() && Route::has('admin.dashboard'))
                            <a href="{{ route('admin.dashboard') }}" class="mt-1 block rounded-lg px-3 py-2 text-sm text-slate-700 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800">Admin Panel</a>
                        @elseif (auth()->user()->isStudent())
                            @if (Route::has('student.dashboard'))
                                <a href="{{ route('student.dashboard') }}" class="mt-1 block rounded-lg px-3 py-2 text-sm text-slate-700 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800">My Dashboard</a>
                            @endif
                            @if (Route::has('student.registrations.index'))
                                <a href="{{ route('student.registrations.index') }}" class="block rounded-lg px-3 py-2 text-sm text-slate-700 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800">My Registrations</a>
                            @endif
                            @if (Route::has('student.profile.edit'))
                                <a href="{{ route('student.profile.edit') }}" class="block rounded-lg px-3 py-2 text-sm text-slate-700 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800">Profile</a>
                            @endif
                        @endif
                        <form method="POST" action="{{ route('logout') }}" class="mt-1 border-t border-slate-100 pt-1 dark:border-slate-800">
                            @csrf
                            <button type="submit" class="w-full rounded-lg px-3 py-2 text-left text-sm font-medium text-rejected-600 hover:bg-rejected-50 dark:text-red-400 dark:hover:bg-rejected-500/10">Sign out</button>
                        </form>
                    </div>
                </div>
            @endauth

            <button type="button" @click="mobile = !mobile"
                    class="grid h-10 w-10 place-items-center rounded-lg text-slate-600 transition hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-800 md:hidden"
                    aria-label="Toggle navigation">
                <svg x-show="!mobile" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
                <svg x-show="mobile" x-cloak class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18M6 6l12 12"/></svg>
            </button>
        </div>
    </div>

    <div x-show="mobile" x-cloak x-transition class="border-t border-slate-200 bg-white px-4 py-3 dark:border-slate-800 dark:bg-slate-950 md:hidden">
        <a href="{{ route('home') }}" class="block rounded-lg px-3 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-900">Home</a>
        <a href="{{ route('events.index') }}" class="block rounded-lg px-3 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-900">Events</a>
        <a href="{{ route('contact.show') }}" class="block rounded-lg px-3 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-900">Contact</a>
        @auth
            @if (auth()->user()->isAdmin() && Route::has('admin.dashboard'))
                <a href="{{ route('admin.dashboard') }}" class="block rounded-lg px-3 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-900">Admin Panel</a>
            @elseif (Route::has('student.dashboard'))
                <a href="{{ route('student.dashboard') }}" class="block rounded-lg px-3 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-900">My Dashboard</a>
            @endif
            @if (Route::has('student.registrations.index'))
                <a href="{{ route('student.registrations.index') }}" class="block rounded-lg px-3 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-900">My Registrations</a>
            @endif
            <form method="POST" action="{{ route('logout') }}" class="mt-1 border-t border-slate-100 pt-1 dark:border-slate-800">
                @csrf
                <button type="submit" class="block w-full rounded-lg px-3 py-2.5 text-left text-sm font-medium text-rejected-600 hover:bg-rejected-50 dark:text-red-400 dark:hover:bg-rejected-500/10">Sign out</button>
            </form>
        @else
            <div class="mt-2 flex gap-2 border-t border-slate-100 pt-3 dark:border-slate-800">
                <a href="{{ route('login') }}" class="btn-secondary flex-1">Sign in</a>
                <a href="{{ route('register') }}" class="btn-primary flex-1">Get started</a>
            </div>
        @endauth
    </div>
</nav>

<main class="flex-1">
    @include('partials.toast')
    @yield('content')
</main>

<footer class="mt-auto border-t border-slate-200 bg-white dark:border-slate-800 dark:bg-slate-900/40">
    <div class="h-1 gradient-spectrum-animated"></div>
    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-4">
            <div class="lg:col-span-2">
                <div class="flex items-center gap-2.5 font-bold text-slate-900 dark:text-white">
                    <span class="gradient-spectrum grid h-8 w-8 place-items-center rounded-lg text-white">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/></svg>
                    </span>
                    {{ config('app.name') }}
                </div>
                <p class="mt-3 max-w-sm text-sm leading-relaxed text-slate-500 dark:text-slate-400">
                    International Fiesta 2026 — <span class="font-semibold text-slate-700 dark:text-slate-200">"Voices Unmuted: The Spectrum"</span>.
                    One platform for every event, registration and unforgettable campus moment.
                </p>
            </div>
            <div>
                <p class="text-sm font-semibold text-slate-900 dark:text-white">Explore</p>
                <ul class="mt-3 space-y-2 text-sm text-slate-500 dark:text-slate-400">
                    <li><a href="{{ route('events.index') }}" class="transition hover:text-brand-600 dark:hover:text-brand-400">All events</a></li>
                    <li><a href="{{ route('contact.show') }}" class="transition hover:text-brand-600 dark:hover:text-brand-400">Contact us</a></li>
                    <li><a href="{{ route('login') }}" class="transition hover:text-brand-600 dark:hover:text-brand-400">Sign in</a></li>
                    <li><a href="{{ route('register') }}" class="transition hover:text-brand-600 dark:hover:text-brand-400">Create account</a></li>
                </ul>
            </div>
            <div>
                <p class="text-sm font-semibold text-slate-900 dark:text-white">Fiesta 2026</p>
                <ul class="mt-3 space-y-2 text-sm text-slate-500 dark:text-slate-400">
                    <li>Sports · Seminars · Workshops</li>
                    <li>Cultural · Technical · Literary</li>
                    <li>Campus-wide, all departments</li>
                </ul>
            </div>
        </div>
        <div class="mt-8 border-t border-slate-100 pt-6 text-xs text-slate-400 dark:border-slate-800 dark:text-slate-500">
            &copy; {{ date('Y') }} {{ config('app.name') }} · College Event Management System
        </div>
    </div>
</footer>

@stack('scripts')
</body>
</html>

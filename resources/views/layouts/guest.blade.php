<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Sign in') · {{ config('app.name') }}</title>
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
<body class="min-h-screen bg-slate-50 transition-colors duration-300 dark:bg-slate-950">
<div class="flex min-h-screen flex-col lg:flex-row">

    {{-- Spectrum brand panel --}}
    <div class="gradient-spectrum-animated relative flex flex-col justify-between overflow-hidden p-8 text-white lg:w-[42%] lg:p-12">
        <div class="anim-blob absolute -left-24 -top-24 h-72 w-72 rounded-full bg-white/15 blur-3xl"></div>
        <div class="anim-blob absolute -bottom-32 -right-16 h-96 w-96 rounded-full bg-white/10 blur-3xl" style="animation-delay: -7s"></div>

        <a href="{{ route('home') }}" class="relative flex items-center gap-3 font-bold tracking-tight">
            <span class="grid h-10 w-10 place-items-center rounded-xl bg-white/20 backdrop-blur">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/></svg>
            </span>
            {{ config('app.name') }}
        </a>

        <div class="relative hidden lg:block anim-fade-up">
            <p class="text-sm font-semibold uppercase tracking-[0.25em] text-white/80">International Fiesta 2026</p>
            <h1 class="mt-3 text-4xl font-bold leading-tight xl:text-5xl">
                Voices Unmuted:<br>The Spectrum
            </h1>
            <p class="mt-4 max-w-md text-white/85">
                Browse events, register in seconds, and track every approval — all in one place built for campus life.
            </p>
            <div class="mt-8 flex items-center gap-3 text-sm text-white/75">
                <span class="anim-dot inline-block h-2 w-2 rounded-full bg-amber-300"></span>
                {{ \App\Models\Event::upcoming()->count() }} events live on the calendar
            </div>
        </div>

        <p class="relative text-xs text-white/70 lg:text-sm">
            &copy; {{ date('Y') }} {{ config('app.name') }} · College Event Management System
        </p>
    </div>

    {{-- Form panel --}}
    <main class="relative flex flex-1 items-center justify-center p-6 sm:p-10">
        <div class="absolute right-5 top-5">
            @include('partials.theme-toggle')
        </div>
        <div class="w-full max-w-md anim-fade-up">
            @yield('content')
        </div>
    </main>
</div>
</body>
</html>

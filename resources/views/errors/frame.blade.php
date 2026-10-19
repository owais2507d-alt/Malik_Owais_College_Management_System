<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('code') — {{ config('app.name') }}</title>
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="gradient-spectrum flex min-h-screen items-center justify-center p-4">
    <main class="w-full max-w-lg rounded-3xl bg-white/95 p-8 text-center shadow-2xl backdrop-blur sm:p-10">
        <span class="gradient-spectrum mx-auto grid h-14 w-14 place-items-center rounded-2xl text-white shadow-lg">
            <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                @yield('icon')
            </svg>
        </span>

        <p class="mt-6 text-sm font-semibold uppercase tracking-[0.2em] text-brand-600">International Fiesta 2026</p>
        <p class="text-spectrum mt-1 text-6xl font-black">@yield('code')</p>
        <h1 class="mt-2 text-xl font-bold text-slate-900">@yield('message')</h1>
        <p class="mx-auto mt-2 max-w-sm text-sm leading-relaxed text-slate-500">@yield('hint')</p>

        <div class="mt-7 flex flex-wrap justify-center gap-3">
            @yield('actions')
        </div>

        <p class="mt-8 text-xs font-medium text-slate-400">Voices Unmuted: The Spectrum</p>
    </main>
</body>
</html>

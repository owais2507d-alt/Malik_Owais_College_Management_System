<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="grid min-h-screen place-items-center bg-slate-50 p-6 text-center">
    <div>
        <p class="text-sm font-semibold uppercase tracking-[0.25em] text-brand-600">International Fiesta 2026</p>
        <h1 class="mt-3 text-4xl font-bold text-slate-900 sm:text-5xl">Voices Unmuted: <span class="text-spectrum">The Spectrum</span></h1>
        <p class="mx-auto mt-4 max-w-xl text-slate-500">The full landing page is being assembled. Everything works fine already.</p>
    </div>
</body>
</html>

@extends('layouts.guest')

@section('title', 'Sign in')

@section('content')
<div>
    <h1 class="text-2xl font-bold tracking-tight text-slate-900">Welcome back</h1>
    <p class="mt-1.5 text-sm text-slate-500">Sign in to browse events and manage your registrations.</p>

    @if (session('status'))
        <div class="mt-5 rounded-lg border border-approved-500/30 bg-approved-50 px-4 py-3 text-sm text-approved-600">
            {{ session('status') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mt-5 rounded-lg border border-rejected-500/30 bg-rejected-50 px-4 py-3 text-sm text-rejected-600">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="mt-6 space-y-4">
        @csrf

        <div>
            <label for="email" class="label">Email address</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                   autocomplete="username" placeholder="you@college.edu" class="input">
            @error('email')
                <p class="mt-1.5 text-xs text-rejected-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password" class="label">Password</label>
            <div class="relative" x-data="{ show: false }">
                <input id="password" :type="show ? 'text' : 'password'" name="password" required
                       autocomplete="current-password" placeholder="••••••••" class="input pr-11">
                <button type="button" @click="show = !show"
                        class="absolute inset-y-0 right-0 grid w-11 place-items-center text-slate-400 hover:text-slate-600"
                        :aria-label="show ? 'Hide password' : 'Show password'">
                    <svg x-show="!show" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                    <svg x-show="show" x-cloak class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"/><path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c6.5 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"/><path d="M6.61 6.61A13.53 13.53 0 0 0 2 12s3.5 7 10 7a9.74 9.74 0 0 0 5.39-1.61"/><path d="m2 2 20 20"/></svg>
                </button>
            </div>
            @error('password')
                <p class="mt-1.5 text-xs text-rejected-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center justify-between">
            <label class="flex cursor-pointer items-center gap-2 text-sm text-slate-600">
                <input type="checkbox" name="remember" value="1" class="h-4 w-4 rounded border-slate-300 text-brand-600 focus:ring-brand-500">
                Remember me
            </label>
        </div>

        <button type="submit" class="btn-primary w-full">Sign in</button>
    </form>

    <p class="mt-6 text-center text-sm text-slate-500">
        New to Fiesta?
        <a href="{{ route('register') }}" class="font-semibold text-brand-600 hover:text-brand-700">Create a student account</a>
    </p>
</div>
@endsection

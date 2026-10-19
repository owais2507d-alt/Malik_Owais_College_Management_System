@extends('layouts.app')

@section('title', 'Contact us — International Fiesta 2026')

@section('content')
<section class="relative overflow-hidden border-b border-slate-200 bg-sidebar text-white dark:border-slate-800">
    <img src="{{ asset('images/contact-team.jpg') }}" alt="" class="absolute inset-0 h-full w-full object-cover opacity-25">
    <div class="absolute inset-0 bg-gradient-to-r from-sidebar via-sidebar/85 to-sidebar/40"></div>
    <div class="anim-blob absolute -right-20 -top-16 h-72 w-72 rounded-full bg-fuchsia-500/25 blur-3xl"></div>

    <div class="relative mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
        <p class="anim-fade-up text-sm font-semibold uppercase tracking-[0.2em] text-white/70">Get in touch</p>
        <h1 class="anim-fade-up mt-2 text-3xl font-bold tracking-tight sm:text-4xl" style="animation-delay: 80ms">
            Contact the <span class="text-spectrum">Fiesta team</span>
        </h1>
        <p class="anim-fade-up mt-3 max-w-2xl text-white/75" style="animation-delay: 160ms">
            Questions about a registration, want to host your own event, or found an issue?
            Drop us a message — we usually reply within a day.
        </p>
    </div>
    <div class="absolute bottom-0 left-0 right-0 h-1 gradient-spectrum-animated"></div>
</section>

<section class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
    <div class="grid gap-8 lg:grid-cols-5">

        {{-- Info panel --}}
        <div class="space-y-4 lg:col-span-2">
            <div class="reveal card flex items-start gap-4 p-5">
                <span class="gradient-spectrum grid h-11 w-11 shrink-0 place-items-center rounded-xl text-white shadow-sm">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                </span>
                <div>
                    <h2 class="font-bold text-slate-900 dark:text-white">Email us</h2>
                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">fiesta.helpdesk@college.edu</p>
                    <p class="text-xs text-slate-400 dark:text-slate-500">Best for approvals, refunds & event info</p>
                </div>
            </div>

            <div class="reveal card flex items-start gap-4 p-5" style="--reveal-delay: 90ms">
                <span class="gradient-spectrum grid h-11 w-11 shrink-0 place-items-center rounded-xl text-white shadow-sm">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                </span>
                <div>
                    <h2 class="font-bold text-slate-900 dark:text-white">Student Union Office</h2>
                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Block C, Ground Floor, Main Campus</p>
                    <p class="text-xs text-slate-400 dark:text-slate-500">Mon–Fri · 10:00 AM – 4:00 PM</p>
                </div>
            </div>

            <div class="reveal card flex items-start gap-4 p-5" style="--reveal-delay: 180ms">
                <span class="gradient-spectrum grid h-11 w-11 shrink-0 place-items-center rounded-xl text-white shadow-sm">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92Z"/></svg>
                </span>
                <div>
                    <h2 class="font-bold text-slate-900 dark:text-white">Hotline</h2>
                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">+92 300 1234567</p>
                    <p class="text-xs text-slate-400 dark:text-slate-500">Event days only · 9 AM – 6 PM</p>
                </div>
            </div>

            <div class="reveal card overflow-hidden p-0" style="--reveal-delay: 270ms">
                <img src="{{ asset('images/contact-team.jpg') }}" alt="Fiesta organizing team" class="h-44 w-full object-cover" loading="lazy">
                <p class="px-5 py-3 text-xs text-slate-500 dark:text-slate-400">The student team behind International Fiesta 2026</p>
            </div>
        </div>

        {{-- Form --}}
        <div class="reveal lg:col-span-3" style="--reveal-delay: 120ms">
            <div class="card p-6 sm:p-8">
                <h2 class="text-lg font-bold text-slate-900 dark:text-white">Send a message</h2>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">All fields marked required must be filled in.</p>

                @if (session('status'))
                    <div class="mt-5 rounded-xl border border-approved-500/30 bg-approved-50 px-4 py-3 text-sm text-approved-600 dark:border-approved-500/30 dark:bg-approved-500/10 dark:text-emerald-300">
                        {{ session('status') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="mt-5 rounded-xl border border-rejected-500/30 bg-rejected-50 px-4 py-3 text-sm text-rejected-600 dark:border-rejected-500/30 dark:bg-rejected-500/10 dark:text-red-300">
                        {{ session('error') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('contact.store') }}" class="mt-6 space-y-4" x-data="{ sending: false }" @submit="sending = true">
                    @csrf

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label for="name" class="label">Your name *</label>
                            <input id="name" type="text" name="name" value="{{ old('name', auth()->user()?->name) }}" required maxlength="255"
                                   placeholder="Alex Johnson" class="input">
                            @error('name')
                                <p class="mt-1.5 text-xs text-rejected-600 dark:text-red-300">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="email" class="label">Email *</label>
                            <input id="email" type="email" name="email" value="{{ old('email', auth()->user()?->email) }}" required maxlength="255"
                                   placeholder="you@college.edu" class="input">
                            @error('email')
                                <p class="mt-1.5 text-xs text-rejected-600 dark:text-red-300">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="subject" class="label">Subject <span class="font-normal text-slate-400">(optional)</span></label>
                        <input id="subject" type="text" name="subject" value="{{ old('subject') }}" maxlength="255"
                               placeholder="e.g. Question about workshop seats" class="input">
                        @error('subject')
                            <p class="mt-1.5 text-xs text-rejected-600 dark:text-red-300">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="message" class="label">Message *</label>
                        <textarea id="message" name="message" rows="6" required minlength="10" maxlength="5000"
                                  class="input resize-y leading-relaxed"
                                  placeholder="Tell us what's on your mind...">{{ old('message') }}</textarea>
                        @error('message')
                            <p class="mt-1.5 text-xs text-rejected-600 dark:text-red-300">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-between gap-4 pt-1">
                        <p class="text-xs text-slate-400 dark:text-slate-500">We'll never share your email.</p>
                        <button type="submit" class="btn-primary shine" :disabled="sending">
                            <span x-show="!sending">Send message</span>
                            <span x-show="sending" x-cloak>Sending…</span>
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m22 2-7 20-4-9-9-4Z"/><path d="M22 2 11 13"/></svg>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection

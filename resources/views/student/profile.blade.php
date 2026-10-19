@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
@include('partials.student-nav')

<div class="mx-auto max-w-5xl px-4 py-8 sm:px-6 lg:px-8">
    <div>
        <p class="text-sm font-semibold uppercase tracking-widest text-brand-600">Student panel</p>
        <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">My profile</h1>
        <p class="mt-1 text-slate-500">Keep your details up to date so organizers can reach you.</p>
    </div>

    <div class="mt-7 grid gap-6 lg:grid-cols-3">
        {{-- Identity card --}}
        <div class="card p-6 text-center lg:col-span-1 lg:h-fit">
            <span class="gradient-spectrum mx-auto grid h-20 w-20 place-items-center rounded-full text-2xl font-bold text-white shadow-lg">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </span>
            <h2 class="mt-4 text-lg font-bold text-slate-900">{{ $user->name }}</h2>
            <p class="text-sm text-slate-500">{{ $user->email }}</p>
            <span class="badge-upcoming mt-3 capitalize">{{ $user->role }}</span>

            <dl class="mt-5 space-y-2 border-t border-slate-100 pt-4 text-left text-sm">
                <div class="flex justify-between gap-2">
                    <dt class="text-slate-500">Phone</dt>
                    <dd class="font-medium text-slate-800">{{ $user->phone ?: '—' }}</dd>
                </div>
                <div class="flex justify-between gap-2">
                    <dt class="text-slate-500">Member since</dt>
                    <dd class="font-medium text-slate-800">{{ $user->created_at->format('M Y') }}</dd>
                </div>
                <div class="flex justify-between gap-2">
                    <dt class="text-slate-500">Registrations</dt>
                    <dd class="font-medium text-slate-800">{{ $user->registrations()->count() }}</dd>
                </div>
            </dl>
        </div>

        <div class="space-y-6 lg:col-span-2">
            {{-- Personal details --}}
            <div class="card p-6">
                <h2 class="font-bold text-slate-900">Personal details</h2>
                <p class="mt-1 text-sm text-slate-500">Name, email and phone used for event communications.</p>

                <form method="POST" action="{{ route('student.profile.update') }}" class="mt-5 space-y-4">
                    @csrf
                    @method('PUT')

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label for="name" class="label">Full name</label>
                            <input id="name" type="text" name="name" value="{{ old('name', $user->name) }}" required class="input">
                            @error('name')
                                <p class="mt-1.5 text-xs text-rejected-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="email" class="label">Email address</label>
                            <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}" required class="input">
                            @error('email')
                                <p class="mt-1.5 text-xs text-rejected-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="phone" class="label">Phone <span class="font-normal text-slate-400">(optional)</span></label>
                        <input id="phone" type="text" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="+92 3xx xxxxxxx" class="input">
                        @error('phone')
                            <p class="mt-1.5 text-xs text-rejected-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="btn-primary">Save changes</button>
                    </div>
                </form>
            </div>

            {{-- Password --}}
            <div class="card p-6">
                <h2 class="font-bold text-slate-900">Change password</h2>
                <p class="mt-1 text-sm text-slate-500">Enter your current password, then choose a new one (min 8 characters).</p>

                <form method="POST" action="{{ route('student.profile.password') }}" class="mt-5 space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="current_password" class="label">Current password</label>
                        <input id="current_password" type="password" name="current_password" class="input" autocomplete="current-password">
                        @error('current_password')
                            <p class="mt-1.5 text-xs text-rejected-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label for="new_password" class="label">New password</label>
                            <input id="new_password" type="password" name="new_password" class="input" autocomplete="new-password">
                            @error('new_password')
                                <p class="mt-1.5 text-xs text-rejected-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="new_password_confirmation" class="label">Confirm new password</label>
                            <input id="new_password_confirmation" type="password" name="new_password_confirmation" class="input" autocomplete="new-password">
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="btn-secondary">Update password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

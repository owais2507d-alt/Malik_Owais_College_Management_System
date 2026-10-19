@extends('layouts.admin')

@section('page_title', $event->exists ? 'Edit Event' : 'Create Event')

@section('content')
<div class="flex flex-wrap items-center justify-between gap-3">
    <div>
        <a href="{{ route('admin.events.index') }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-slate-500 hover:text-brand-600">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 19-7-7 7-7M19 12H5"/></svg>
            Back to events
        </a>
        <p class="mt-1 text-sm text-slate-500">{{ $event->exists ? 'Update the details of this event.' : 'Fill in the details to publish a new college event.' }}</p>
    </div>
</div>

@if ($errors->any())
    <div class="mt-4 rounded-xl border border-rejected-500/30 bg-rejected-50 px-4 py-3 text-sm text-rejected-600">
        <p class="font-semibold">Please fix the following:</p>
        <ul class="mt-1 list-inside list-disc space-y-0.5">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST"
      action="{{ $event->exists ? route('admin.events.update', $event) : route('admin.events.store') }}"
      enctype="multipart/form-data"
      class="mt-5 grid gap-6 lg:grid-cols-3">
    @csrf
    @if ($event->exists)
        @method('PUT')
    @endif

    <div class="space-y-6 lg:col-span-2">
        <div class="card p-6">
            <h2 class="font-bold text-slate-900">Event details</h2>

            <div class="mt-5 space-y-4">
                <div>
                    <label for="title" class="label">Event title</label>
                    <input id="title" type="text" name="title" value="{{ old('title', $event->title) }}" required maxlength="255"
                           placeholder="e.g. Spectrum Hackathon 2026" class="input">
                    @error('title')
                        <p class="mt-1.5 text-xs text-rejected-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="description" class="label">Description</label>
                    <textarea id="description" name="description" rows="6" required
                              class="input resize-y leading-relaxed"
                              placeholder="What is this event about? Schedule, highlights, requirements...">{{ old('description', $event->description) }}</textarea>
                    @error('description')
                        <p class="mt-1.5 text-xs text-rejected-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label for="category_id" class="label">Category</label>
                        <select id="category_id" name="category_id" required class="input">
                            <option value="">Select category</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" @selected((string) old('category_id', $event->category_id) === (string) $category->id)>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <p class="mt-1.5 text-xs text-rejected-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="venue" class="label">Venue</label>
                        <input id="venue" type="text" name="venue" value="{{ old('venue', $event->venue) }}" required maxlength="255"
                               placeholder="e.g. Main Auditorium" class="input">
                        @error('venue')
                            <p class="mt-1.5 text-xs text-rejected-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="card p-6">
            <h2 class="font-bold text-slate-900">Schedule &amp; capacity</h2>

            <div class="mt-5 grid gap-4 sm:grid-cols-2">
                <div>
                    <label for="event_date" class="label">Event date</label>
                    <input id="event_date" type="date" name="event_date"
                           value="{{ old('event_date', $event->exists ? $event->event_date->format('Y-m-d') : '') }}" required class="input">
                    @error('event_date')
                        <p class="mt-1.5 text-xs text-rejected-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="registration_deadline" class="label">Registration deadline</label>
                    <input id="registration_deadline" type="datetime-local" name="registration_deadline"
                           value="{{ old('registration_deadline', $event->exists ? str_replace(' ', 'T', substr($event->registration_deadline->toDateTimeString(), 0, 16)) : '') }}" required class="input">
                    @error('registration_deadline')
                        <p class="mt-1.5 text-xs text-rejected-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="start_time" class="label">Start time</label>
                    <input id="start_time" type="time" name="start_time"
                           value="{{ old('start_time', $event->exists ? \Illuminate\Support\Carbon::parse($event->start_time)->format('H:i') : '') }}" required class="input">
                    @error('start_time')
                        <p class="mt-1.5 text-xs text-rejected-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="end_time" class="label">End time</label>
                    <input id="end_time" type="time" name="end_time"
                           value="{{ old('end_time', $event->exists ? \Illuminate\Support\Carbon::parse($event->end_time)->format('H:i') : '') }}" required class="input">
                    @error('end_time')
                        <p class="mt-1.5 text-xs text-rejected-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="max_participants" class="label">Max participants</label>
                    <input id="max_participants" type="number" name="max_participants" min="1" max="100000"
                           value="{{ old('max_participants', $event->max_participants) }}" required class="input">
                    @error('max_participants')
                        <p class="mt-1.5 text-xs text-rejected-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="status" class="label">Status</label>
                    <select id="status" name="status" required class="input">
                        @foreach (['upcoming', 'ongoing', 'completed'] as $option)
                            <option value="{{ $option }}" @selected(old('status', $event->status ?: 'upcoming') === $option)>
                                {{ ucfirst($option) }}
                            </option>
                        @endforeach
                    </select>
                    @error('status')
                        <p class="mt-1.5 text-xs text-rejected-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>
    </div>

    <div class="space-y-6">
        <div class="card p-6" x-data="{ preview: '{{ $event->imageUrl() ?? '' }}' }">
            <h2 class="font-bold text-slate-900">Event poster</h2>
            <p class="mt-1 text-xs text-slate-500">JPG, PNG or WebP — max 2 MB. Recommended size 1200×630.</p>

            <div class="mt-4">
                <div class="grid aspect-video w-full place-items-center overflow-hidden rounded-xl border-2 border-dashed border-slate-300 bg-slate-50">
                    <img x-show="preview" :src="preview" alt="Poster preview" class="h-full w-full object-cover" x-cloak>
                    <div x-show="!preview" class="text-center text-slate-400">
                        <svg class="mx-auto h-9 w-9" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="3" rx="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.1-3.1a2 2 0 0 0-2.8 0L6 21"/></svg>
                        <p class="mt-1.5 text-xs">No poster selected</p>
                    </div>
                </div>

                <label for="image" class="label mt-4">
                    <span class="btn-secondary w-full cursor-pointer justify-center">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M17 8l-5-5-5 5M12 3v12"/></svg>
                        Choose image
                    </span>
                    <input id="image" type="file" name="image" accept="image/jpeg,image/png,image/webp" class="sr-only"
                           @change="preview = $event.target.files[0] ? URL.createObjectURL($event.target.files[0]) : preview">
                </label>
                @error('image')
                    <p class="mt-1.5 text-xs text-rejected-600">{{ $message }}</p>
                @enderror
                @if ($event->exists && $event->image)
                    <label class="mt-3 flex cursor-pointer items-center gap-2 text-xs text-slate-600">
                        <input type="checkbox" name="_remove_image" value="1" class="h-4 w-4 rounded border-slate-300 text-rejected-600 focus:ring-rejected-500">
                        Remove current poster
                    </label>
                @endif
            </div>
        </div>

        <div class="card bg-gradient-to-br from-brand-600 to-fuchsia-600 p-6 text-white">
            <h3 class="font-bold">Tips</h3>
            <ul class="mt-3 space-y-2 text-sm text-white/85">
                <li>• Set the deadline before the event date (enforced).</li>
                <li>• Seats fill up fast — capacity blocks new registrations.</li>
                <li>• Switch status to <strong>completed</strong> after the event wraps.</li>
            </ul>
        </div>

        <div class="flex gap-3">
            <button type="submit" class="btn-primary flex-1">{{ $event->exists ? 'Save changes' : 'Create event' }}</button>
            <a href="{{ route('admin.events.index') }}" class="btn-secondary">Cancel</a>
        </div>
    </div>
</form>
@endsection

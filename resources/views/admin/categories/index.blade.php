@extends('layouts.admin')

@section('page_title', 'Event Categories')

@section('content')
@if ($errors->any())
    <div class="mb-4 rounded-xl border border-rejected-500/30 bg-rejected-50 px-4 py-3 text-sm text-rejected-600">
        {{ $errors->first() }}
    </div>
@endif

<div class="flex flex-wrap items-end justify-between gap-4" x-data="{ open: false }">
    <p class="text-sm text-slate-500">{{ $categories->total() }} categor{{ $categories->total() === 1 ? 'y' : 'ies' }} organizing college events</p>
    <button type="button" @click="open = true" class="btn-primary">
        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>
        New category
    </button>

    {{-- Create modal --}}
    <div x-show="open" x-cloak x-transition.opacity class="fixed inset-0 z-50 grid place-items-center bg-slate-900/50 p-4 backdrop-blur-sm">
        <div x-show="open" x-transition class="w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl">
            <div class="flex items-center justify-between">
                <h3 class="font-bold text-slate-900">New category</h3>
                <button type="button" @click="open = false" class="text-slate-400 hover:text-slate-600" aria-label="Close">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18M6 6l12 12"/></svg>
                </button>
            </div>
            <form method="POST" action="{{ route('admin.categories.store') }}" class="mt-4 space-y-4">
                @csrf
                <div>
                    <label for="cat_name" class="label">Name</label>
                    <input id="cat_name" type="text" name="name" required maxlength="255" placeholder="e.g. Music &amp; Dance" class="input">
                    @error('name')
                        <p class="mt-1.5 text-xs text-rejected-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="cat_description" class="label">Description <span class="font-normal text-slate-400">(optional)</span></label>
                    <textarea id="cat_description" name="description" rows="3" class="input resize-y" maxlength="1000" placeholder="Short blurb about this category"></textarea>
                    @error('description')
                        <p class="mt-1.5 text-xs text-rejected-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" @click="open = false" class="btn-secondary">Cancel</button>
                    <button type="submit" class="btn-primary">Create category</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="card mt-5 overflow-hidden" x-data="{ editing: null, deleting: null }">
    @if ($categories->isEmpty())
        <div class="p-12 text-center text-slate-500">No categories yet — create the first one.</div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-xs font-semibold uppercase tracking-wider text-slate-500">
                    <tr>
                        <th class="px-6 py-3.5">Category</th>
                        <th class="px-4 py-3.5">Description</th>
                        <th class="px-4 py-3.5">Events</th>
                        <th class="px-4 py-3.5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach ($categories as $category)
                        <tr class="hover:bg-slate-50/70">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <span class="gradient-spectrum grid h-9 w-9 shrink-0 place-items-center rounded-lg text-white">
                                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 7V4h16v3M9 20h6M12 4v16"/></svg>
                                    </span>
                                    <span class="font-semibold text-slate-900">{{ $category->name }}</span>
                                </div>
                            </td>
                            <td class="max-w-md px-4 py-4 text-slate-500">
                                <span class="line-clamp-2">{{ $category->description ?: '—' }}</span>
                            </td>
                            <td class="px-4 py-4">
                                <span class="badge-category">{{ $category->events_count }} event{{ $category->events_count === 1 ? '' : 's' }}</span>
                            </td>
                            <td class="whitespace-nowrap px-4 py-4 text-right">
                                <div class="inline-flex items-center gap-1">
                                    <button type="button"
                                            @click="editing = { id: {{ $category->id }}, name: @js($category->name), description: @js($category->description) }"
                                            class="grid h-8 w-8 place-items-center rounded-lg text-slate-500 transition hover:bg-brand-50 hover:text-brand-600" title="Edit">
                                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/></svg>
                                    </button>
                                    <button type="button"
                                            @click="deleting = { id: {{ $category->id }}, name: @js($category->name), count: {{ $category->events_count }} }"
                                            class="grid h-8 w-8 place-items-center rounded-lg text-slate-500 transition hover:bg-rejected-50 hover:text-rejected-600" title="Delete">
                                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    {{-- Edit modal --}}
    <div x-show="editing" x-cloak x-transition.opacity class="fixed inset-0 z-50 grid place-items-center bg-slate-900/50 p-4 backdrop-blur-sm">
        <div x-show="editing" x-transition class="w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl" x-cloak>
            <div class="flex items-center justify-between">
                <h3 class="font-bold text-slate-900">Edit category</h3>
                <button type="button" @click="editing = null" class="text-slate-400 hover:text-slate-600" aria-label="Close">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18M6 6l12 12"/></svg>
                </button>
            </div>
            <form method="POST" :action="'{{ url('admin/categories') }}/' + editing?.id" class="mt-4 space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="label" for="edit_name">Name</label>
                    <input id="edit_name" type="text" name="name" required maxlength="255" class="input" x-model="editing.name">
                    @error('name')
                        <p class="mt-1.5 text-xs text-rejected-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="label" for="edit_description">Description</label>
                    <textarea id="edit_description" name="description" rows="3" class="input resize-y" maxlength="1000" x-model="editing.description"></textarea>
                    @error('description')
                        <p class="mt-1.5 text-xs text-rejected-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" @click="editing = null" class="btn-secondary">Cancel</button>
                    <button type="submit" class="btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Delete modal --}}
    <div x-show="deleting" x-cloak x-transition.opacity class="fixed inset-0 z-50 grid place-items-center bg-slate-900/50 p-4 backdrop-blur-sm">
        <div x-show="deleting" x-transition class="w-full max-w-sm rounded-2xl bg-white p-6 shadow-2xl">
            <span class="grid h-11 w-11 place-items-center rounded-full bg-rejected-50 text-rejected-600">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
            </span>
            <h3 class="mt-4 font-bold text-slate-900">Delete category?</h3>
            <p class="mt-1 text-sm text-slate-500">
                <span x-text="deleting?.name" class="font-semibold"></span>
                <span x-show="deleting?.count > 0" class="text-rejected-600"> still has <span x-text="deleting?.count"></span> event(s) and cannot be deleted.</span>
                <span x-show="deleting?.count == 0">This category has no events and can be safely removed.</span>
            </p>
            <form method="POST" :action="'{{ url('admin/categories') }}/' + deleting?.id" class="mt-5 flex gap-2">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-danger flex-1" :disabled="deleting?.count > 0">Delete</button>
                <button type="button" @click="deleting = null" class="btn-secondary flex-1">Cancel</button>
            </form>
        </div>
    </div>
</div>

<div class="mt-6">
    {{ $categories->links() }}
</div>
@endsection

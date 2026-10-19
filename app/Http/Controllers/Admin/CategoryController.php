<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EventCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = EventCategory::query()
            ->withCount(['events as events_count' => fn ($query) => $query->whereNull('deleted_at')])
            ->orderBy('name')
            ->paginate(10);

        return view('admin.categories.index', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:event_categories,name'],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        EventCategory::create($data);

        Cache::forget('fiesta.home.stats');

        return back()->with('status', 'Category created successfully.');
    }

    public function update(Request $request, EventCategory $category): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:event_categories,name,'.$category->id],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        $category->update($data);

        Cache::forget('fiesta.home.stats');

        return back()->with('status', 'Category updated successfully.');
    }

    public function destroy(EventCategory $category): RedirectResponse
    {
        if ($category->activeEvents()->exists()) {
            return back()->with('error', 'Cannot delete "'.$category->name.'" — it still has events. Reassign or delete them first.');
        }

        $category->delete();

        Cache::forget('fiesta.home.stats');

        return back()->with('status', 'Category deleted successfully.');
    }
}

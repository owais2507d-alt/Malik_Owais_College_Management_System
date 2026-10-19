<div class="border-b border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <nav class="-mb-px flex gap-1 overflow-x-auto py-3" aria-label="Student navigation">
            @foreach ([
                'student.dashboard' => 'Dashboard',
                'student.registrations.index' => 'My Registrations',
                'student.profile.edit' => 'Profile',
            ] as $route => $label)
                <a href="{{ route($route) }}"
                   class="whitespace-nowrap rounded-lg px-4 py-2 text-sm font-semibold transition {{ request()->routeIs($route) ? 'bg-brand-600 text-white shadow-sm' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 hover:text-slate-900 dark:text-white' }}">
                    {{ $label }}
                </a>
            @endforeach
        </nav>
    </div>
</div>

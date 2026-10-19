@if (session('status'))
    <div class="pointer-events-none fixed inset-x-4 top-20 z-50 flex justify-center sm:inset-x-auto sm:right-4 sm:justify-end"
         x-data="{ show: true }" x-init="setTimeout(() => show = false, 5000)" x-show="show" x-transition.opacity>
        <div class="pointer-events-auto flex max-w-sm items-start gap-3 rounded-xl border border-approved-500/30 bg-white px-4 py-3 shadow-lg shadow-slate-900/10">
            <span class="mt-0.5 grid h-6 w-6 shrink-0 place-items-center rounded-full bg-approved-500 text-white">
                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
            </span>
            <p class="text-sm font-medium text-slate-700">{{ session('status') }}</p>
            <button type="button" @click="show = false" class="ml-1 text-slate-400 hover:text-slate-600" aria-label="Dismiss">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18M6 6l12 12"/></svg>
            </button>
        </div>
    </div>
@endif

@if (session('error'))
    <div class="pointer-events-none fixed inset-x-4 top-20 z-50 flex justify-center sm:inset-x-auto sm:right-4 sm:justify-end"
         x-data="{ show: true }" x-init="setTimeout(() => show = false, 6000)" x-show="show" x-transition.opacity>
        <div class="pointer-events-auto flex max-w-sm items-start gap-3 rounded-xl border border-rejected-500/30 bg-white px-4 py-3 shadow-lg shadow-slate-900/10">
            <span class="mt-0.5 grid h-6 w-6 shrink-0 place-items-center rounded-full bg-rejected-500 text-white">
                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18M6 6l12 12"/></svg>
            </span>
            <p class="text-sm font-medium text-slate-700">{{ session('error') }}</p>
            <button type="button" @click="show = false" class="ml-1 text-slate-400 hover:text-slate-600" aria-label="Dismiss">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18M6 6l12 12"/></svg>
            </button>
        </div>
    </div>
@endif

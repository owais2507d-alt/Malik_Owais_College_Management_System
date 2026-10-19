<button type="button"
        x-data="{
            dark: document.documentElement.classList.contains('dark'),
            toggle() {
                this.dark = !this.dark;
                document.documentElement.classList.toggle('dark', this.dark);
                localStorage.setItem('theme', this.dark ? 'dark' : 'light');
            }
        }"
        @click="toggle()"
        class="grid h-9 w-9 shrink-0 place-items-center rounded-lg text-slate-500 ring-1 ring-slate-200 transition hover:scale-105 hover:bg-slate-100 hover:text-brand-600 dark:text-slate-400 dark:ring-slate-700 dark:hover:bg-slate-800 dark:hover:text-brand-400"
        aria-label="Toggle dark mode"
        title="Toggle dark mode">
    <svg x-show="!dark" class="h-[18px] w-[18px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79Z"/></svg>
    <svg x-show="dark" x-cloak class="h-[18px] w-[18px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M6.34 17.66l-1.41 1.41M19.07 4.93l-1.41 1.41"/></svg>
</button>

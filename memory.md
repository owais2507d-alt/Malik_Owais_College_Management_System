# Project Memory — College Event Management System

> Living task log. Updated after every task so any session can resume from here.
> Read this file FIRST when starting a new session.

**Event Theme:** International Fiesta 2026 – "Voices Unmuted: The Spectrum"

## Current Status

- **Phase:** 3c — Admin panel (dashboard, events CRUD + image upload, categories, approvals, participants)
- **Next:** `layouts/admin.blade.php` sidebar shell, Admin\* controllers, FormRequests, admin views, routes under `admin.` prefix

## Stack Decisions (locked)

- Laravel 12 · PHP 8.2 · MySQL (XAMPP, port 3306)
- Tailwind CSS 4 + Alpine.js + Blade (NOT Bootstrap, NOT Livewire)
- Custom TailAdmin-inspired admin theme + Fiesta "spectrum" public theme
- Hand-rolled auth (no Breeze/Fortify)
- Commit per phase, push to GitHub (user provides repo URL)

## Business Rules (from docx/require.md)

- Seats taken = registrations with status `pending` + `approved` (blocks over-capacity)
- Unique constraint on (user_id, event_id) → duplicate registration blocked with friendly error
- Registration blocked after `registration_deadline`
- Event status enum: `upcoming` / `ongoing` / `completed` (auto-updated by scheduled command)
- Registration status enum: `pending` / `approved` / `rejected` / `cancelled`
- Soft deletes on `events` and `event_categories`
- Roles: `admin` (seeded only) / `student` (self-registration)
- Role checks enforced server-side via middleware (`EnsureAdmin`, `EnsureStudent`)
- Image upload: jpg/png/webp ≤ 2MB, stored on `public` disk, `storage:link`

## Completed

- [x] Read requirements (docx/require.md) + tech notes (docx/tech.md)
- [x] Environment check: PHP 8.2.12, Node 24, npm 11, MySQL live on 3306, git 2.52 configured
- [x] Questions resolved: Tailwind+Alpine / MySQL / core+polish scope
- [x] Plan finalized and approved by user
- [x] `git init` (branch main) + `memory.md` created
- [x] Initial skeleton commit pushed to local repo
- [x] MySQL: `.env` switched to `college_management` DB, DB created (utf8mb4), `key:generate` done
- [x] Base migrations ran clean on MySQL (users, cache, jobs)
- [x] `alpinejs@3` installed; `app.js` boots Alpine
- [x] Design system in `resources/css/app.css`: brand scale, sidebar/status colors, `.gradient-spectrum`, `.text-spectrum`, btn/input/badge/card utilities
- [x] `npm run build` green (fixed: Tailwind v4 requires `@utility` not plain `@apply` for reusable classes)
- [x] Migrations: users+role/phone/avatar, event_categories, events (slug, soft deletes), event_registrations (unique user+event)
- [x] Models: User (role helpers), EventCategory, Event (scopes, seats, deadlines), EventRegistration (+cancellable)
- [x] Factories for all 4 models; DatabaseSeeder: admin@fiesta.edu/password, 15 students, 6 categories, 12 events, 160 registrations
- [x] Demo states seeded: workshop FULL (10/10), masterclass DEADLINE PASSED — verified via SQL
- [x] `migrate:fresh --seed` green on MySQL
- [x] Middleware EnsureAdmin/EnsureStudent (abort 403) aliased as `admin`/`student` in bootstrap/app.php
- [x] Auth: LoginController (RateLimiter 5-attempt throttle, session regenerate, role-aware redirect), RegisterController (always role=student), logout
- [x] `layouts/guest.blade.php` (spectrum split-screen) + styled login/register with Alpine password toggles
- [x] Smoke-tested live on :8000 — wrong password shows error, admin login → /, guest pages redirect when authed, register creates student row
- [x] HOME route `/` = HomeController (placeholder view, to be replaced in 3a)
- [x] `layouts/app.blade.php`: sticky navbar, Alpine mobile menu + user dropdown, footer, `partials/toast` flash
- [x] Landing page: spectrum hero + floating "next event" card, stats strip, highlights grid, categories band
- [x] EventController: index (search q + category select + status tabs + paginate 9) / show (slug binding, seats bar, related)
- [x] `partials/event-card.blade.php` shared component (category gradient, status/seat badges)
- [x] Registration store/cancel: capacity → deadline → status → duplicate checks, unique-violation catch, cancel→pending re-register
- [x] LIVE-VERIFIED: search/status/category filters, register toast, duplicate blocked, full blocked, deadline blocked, cancel toast, re-register "Welcome back"
- [x] Student panel: DashboardController (4 stats + next events + recent), RegistrationController@index (status pills + counts), ProfileController (details + separate password endpoint)
- [x] `partials/student-nav.blade.php` pill tabs; views: student/dashboard, student/registrations (rows + inline cancel confirm), student/profile
- [x] LIVE-VERIFIED: student login → /student/dashboard, all pages 200, profile toast, wrong-pw blocked, password change OK, **admin → student routes = 403**
- NOTE: test.student password is now `newpassword123` (was changed during testing); other seed users use `password`
- [x] `layouts/admin.blade.php`: dark sidebar (logo, nav, pending-reg badge), topbar (search-less, avatar dropdown), mobile drawer, breadcrumbs slot
- [x] Admin: DashboardController (4 stats + capacity watchlist + pending approvals), EventController resource CRUD (unique slug, image store/delete, `_remove_image`), CategoryController (destroy guarded when active events exist), RegistrationController (index filters + approve/reject + participants by event, MySQL FIELD status ordering)
- [x] FormRequests StoreEventRequest/UpdateEventRequest (deadline ≥ event_date, start < end, image mimes ≤2MB)
- [x] Views: admin/dashboard, admin/events/{index,form,participants}, admin/categories/index (Alpine create/edit/delete modals + error banner), admin/registrations/index (status tabs, filter form, Alpine decision confirm)
- [x] LIVE-VERIFIED all admin pages 200; event create WITH image upload (file on disk + DB row + toast); invalid POST shows deadline/end-time errors; edit toast; category create/update/delete toasts; approve (302→approved) + reject (302→rejected); soft-delete event sets deleted_at AND removes image file
- [x] SECURITY LIVE-VERIFIED: student → all /admin routes = 403, guest /admin = 302 login, soft-deleted event public page = 404, unknown admin route = 404
- [x] `php artisan storage:link` done; all `h-4.5 w-4.5 h-5 w-5` duplicates fixed
- [x] `npm run build` green (104.6 kB css / 107.4 kB js); `php vendor/bin/pint` auto-fixed 14 files; `php artisan test` 2 passed; / and /admin still 200 after pint
- Phase 3c COMPLETE — commit as "feat: phase 3c admin panel"
- [x] Phase 4: styled error pages `errors/frame.blade.php` (shared spectrum shell) + 403/404/419/500 views (guest vs authed buttons, @vite, fiesta branding)
- [x] `public/favicon.svg` (gradient F tile) linked in all 3 layouts (app/admin/guest)
- [x] `.env` now `APP_DEBUG=false` — required so 500 renders styled page (403/404/419 render styled regardless of debug; 500 only when debug off; tests unaffected via phpunit.xml). Re-enable for local debugging if needed
- [x] LIVE-VERIFIED: custom 404 (unknown URL), 403 (student→/admin shows "Sign in" branch), 419 (POST student/registrations/{slug} w/o CSRF), 500 (temp `/__boom` route, exception hidden from output — route removed after test)
- [x] Consistency: `rel="noopener"` added to target=_blank links (admin registrations list, admin sidebar "View public site"); deleted unused `welcome.blade.php` (no route referenced it); `h-4.5` sweep clean; no dd/dump/TODO leftovers
- [x] Build green (CSS shrank 104→90 kB after welcome.blade removal), pint clean, all key pages 200 (/, /events, guest /login, /admin admin-session, /student/dashboard student-session; cross-role 403s still correct)

## In Progress

- (none — Phase 5 done, awaiting commit)

## Phase 5 — DONE (this session)

- [x] `app/Console/Commands/UpdateEventStatuses.php` (`events:update-status`): past date→completed, today past end→completed, today between start/end→ongoing, future-date ongoing→upcoming reset; scheduled `everyMinute()` in routes/console.php; LIVE-RAN on MySQL ("2 completed, 0 ongoing")
- [x] phpunit.xml: switched tests to **sqlite :memory:** (was commented out — would have wiped the demo MySQL DB via RefreshDatabase!); migrations verified sqlite-safe
- [x] `Admin/RegistrationController` participants ordering: MySQL-only `FIELD()` → portable `CASE status WHEN...` (works on sqlite+MySQL)
- [x] ExampleTest now uses RefreshDatabase (empty sqlite → HomeController must survive zero rows ✓ null-safe)
- [x] 4 new test files (PHPUnit classic, factories for data): AuthTest (8: guest redirects, admin/student login landings, wrong pw, role 403s both directions, self-register always student), RegistrationFlowTest (7: register, duplicate, full, deadline, not-upcoming, cancel+reactivate, cannot cancel others), AdminPanelTest (10: dashboard, create, validation errors incl past date/bad time/0 cap, update, soft delete, approve, reject, category guard, category soft-delete, participants page), EventStatusCommandTest (5: future/past/ongoing-today/not-started/reset)
- [x] Gotcha: categories ALSO soft-delete → `assertDatabaseMissing` fails; use `assertSoftDeleted` (fixed in empty-category test)
- [x] **php artisan test → 32 passed (84 assertions)**; pint clean; build green
- [x] Flash keys everywhere are `status` (success) / `error` (failure) — tests assert those

## Phase 7 — UI/UX OVERHAUL + NEW FEATURES (this session, pushed)

- [x] **Dark/light theme**: `@custom-variant dark` in app.css; anti-FOUC head script (localStorage `theme` + prefers-color-scheme); Alpine toggle partial `partials/theme-toggle.blade.php`; toggle in public navbar + guest auth pages; full dark: variants on layouts/app, guest, home, contact, events index/show, student views, toast, event-card; admin keeps light content + dark sidebar (by design)
- [x] **Animations**: CSS keyframes (fade-up/blob/float/pulse/spectrum-shift) + `.reveal` scroll-reveal via IntersectionObserver in app.js (prefers-reduced-motion respected); `.shine` hover sweep; `.anim-float` hero card; animated gradient footer stripe + guest panel
- [x] **Hero photo**: `public/images/hero-fiesta.jpg` + `contact-team.jpg` (Unsplash, downloaded); hero = photo + overlay + animated blobs; event cards now show real posters when `$event->image` set
- [x] **Contact end-to-end**: migration `contact_messages` + model + ContactController (throttled 5/5min, validated) + public `/contact` page (info cards + form); Admin MessageController inbox (unread-first, expand, mark read/all, delete) + sidebar link with fuchsia unread badge
- [x] **New functions**: `.ics` calendar download `GET /events/{slug}/calendar` (button on event page); participants **CSV export** `GET /admin/events/{event}/participants/export` (button on participants page)
- [x] Gotcha: `response()->streamDownload()` returns **Symfony** StreamedResponse — type-hint Symfony's class not Illuminate's (500 otherwise)
- [x] Gotcha: `assertSee` doesn't work on streamed responses — use `$response->streamedContent()`
- [x] Home landing got a `$g` vs `$f['g']` undefined-variable 500 — fixed; **41 tests pass (121 assertions)**, pint clean, build green (109.6 kB css / 107.8 kB js), all pages live-verified 200

## Next Up

1. Push phase 7 to GitHub (origin already configured)
2. Optional future: admin dark content mode, event gallery, student favorites, email notifications

## Known Issues / Notes

- `.gitignore` already excludes .env, vendor, node_modules — safe for GitHub
- `gh` CLI NOT installed — user will paste empty repo URL for `origin` remote
- Template reference is TailAdmin (commercial) → recreate the look ourselves with Tailwind
- Docs conflict: tech.md mentions Livewire/Redis — NOT used (user chose Blade+Alpine, database cache/queue/session)
- Repo URL not yet provided by user — remote not added; commit locally and push once URL arrives
- Tailwind v4 gotcha: reusable classes must be `@utility` (plain `.class` in components cannot be `@apply`-ed elsewhere)
- Windows PS gotcha: `php artisan tinker --execute` breaks with quotes/`$vars` — verify data with `mysql.exe -e "SQL"` instead
- PHP closures don't auto-capture vars — use `use ($var)` or arrow `fn()` in seeders/collections
- `RateLimiter::throttleKey()` does NOT exist — build key manually (`'login:'.sha1(email.'|'.ip)`)
- PS5.1: `Invoke-WebRequest -MaximumRedirection 0` throws opaque exceptions — test flows by FOLLOWING redirects and reading final ResponseUri
- Eloquent guesses wrong FK: `EventCategory::events()` MUST declare `hasMany(Event::class, 'category_id')`
- `Str::word` doesn't exist → use `Str::words($value, $n, $end)`
- CSRF token rotates after login (session regenerate) — always re-scrape `_token` from a page fetched AFTER auth
- Flash messages live one request: POST response holds them; re-fetching the page consumes them — assert on the POST's own response body
- PS5.1 re-sends PUT on 302 → fake "too many redirects" loop; use curl POST + `_method=PUT` (browser-style) for form tests — that path is what the app actually uses
- Two forms on one page both need their own `_token` scrape (values match) — or scrape the FIRST match

# Project Memory — College Event Management System

> Living task log. Updated after every task so any session can resume from here.
> Read this file FIRST when starting a new session.

**Event Theme:** International Fiesta 2026 – "Voices Unmuted: The Spectrum"

## Current Status

- **Phase:** 3a — Public site (home landing, browse/search events, event details)
- **Next:** layouts/app navbar+footer, HomeController stats/highlights, EventController index/show, toast flash partial

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

## In Progress

- Phase 3a: Public site — app layout, landing page, event browse/search, event details

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

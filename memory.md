# Project Memory — College Event Management System

> Living task log. Updated after every task so any session can resume from here.
> Read this file FIRST when starting a new session.

**Event Theme:** International Fiesta 2026 – "Voices Unmuted: The Spectrum"

## Current Status

- **Phase:** 1 — Database layer (migrations + models + seeders)
- **Next:** event_categories / events / event_registrations migrations, Eloquent models, factories + seeders

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

## In Progress

- Phase 1: Database layer — migrations, models, seeders

## Known Issues / Notes

- `.gitignore` already excludes .env, vendor, node_modules — safe for GitHub
- `gh` CLI NOT installed — user will paste empty repo URL for `origin` remote
- Template reference is TailAdmin (commercial) → recreate the look ourselves with Tailwind
- Docs conflict: tech.md mentions Livewire/Redis — NOT used (user chose Blade+Alpine, database cache/queue/session)
- Repo URL not yet provided by user — remote not added; commit locally and push once URL arrives
- Tailwind v4 gotcha: reusable classes must be `@utility` (plain `.class` in components cannot be `@apply`-ed elsewhere)

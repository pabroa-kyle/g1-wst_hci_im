# Folio: Online Portfolio Template Generator

WST · HCI · IM, Group 1.

Folio lets users enter their portfolio information, save it to an online PostgreSQL database (Supabase), choose one of three templates (Simple, Modern, Creative), preview it, and generate their portfolio.

**Stack:** Laravel 12 (PHP 8.2+), Blade, Tailwind CSS v4, Alpine.js, PostgreSQL on Supabase, Supabase Storage for profile pictures, Railway for hosting.

## Features

- Accounts (register / log in). Each user manages only their own portfolios.
- Six-step form that saves each section separately: Personal (name, photo, email, contact number, address, about me), Education, Skills (with level), Projects, Work Experience, Social & Website Links.
- Three noticeably different templates, rendered live with the user's own data.
- Preview at desktop, tablet, and phone widths; generate; open the full page.
- Manage Portfolio dashboard: view, edit, change template, and delete, with completion progress per section.
- "Start from sample data" for quick testing.

## Required flow → where it lives

| Step | Route |
|---|---|
| Home | `/` |
| Create portfolio | `/portfolios/create` |
| Enter information / save | `/portfolios/{id}/edit/{personal,education,skills,projects,experience,links}` |
| Select template | `/portfolios/{id}/template` |
| Generate | `POST /portfolios/{id}/generate` |
| Preview | `/portfolios/{id}/preview` (full page: `/portfolios/{id}`) |
| Edit / delete (Manage Portfolio) | `/dashboard` |

## Database (normalized)

`users` → `portfolios` (personal info, chosen template, `generated_at`) → `educations`, `skills`, `projects`, `experiences`, `links` (each with `portfolio_id` FK, `ON DELETE CASCADE`, and `position` for ordering). See [the migration](database/migrations/2026_10_08_000100_create_portfolios_tables.php).

## Run locally

```bash
composer install
npm install
cp .env.example .env        # then fill in the values below
php artisan key:generate
php artisan migrate
php artisan storage:link
npm run build
php artisan serve
```

For a quick local run without Supabase, set `DB_CONNECTION=sqlite` (and remove the other `DB_*` lines) and `PHOTO_DISK=public`.

On Windows, enable `extension=pdo_pgsql` and `extension=pgsql` in `php.ini`.

## Supabase setup

1. Create a project at supabase.com.
2. **Database:** open *Connect* → *Session pooler* and copy host, port, user (`postgres.<project-ref>`), and password into the `DB_*` variables. Use the **session pooler** (IPv4), not the direct connection, because Railway can't reach IPv6-only hosts. Keep `DB_SSLMODE=require`.
3. **Storage:** create a **public** bucket named `portfolio-photos`. Under *Project Settings → Storage → S3 Connection*, create an access key and set `SUPABASE_S3_KEY`, `SUPABASE_S3_SECRET`, and `SUPABASE_S3_REGION` (the region shown on that page). Set `SUPABASE_URL=https://<project-ref>.supabase.co` and `PHOTO_DISK=supabase`.
4. Run `php artisan migrate` once against Supabase (Railway also runs it before each deploy).

## Deploy to Railway

1. Push this repository to GitHub (or connect GitLab via a GitHub mirror) and create a Railway project from it. Railway's Railpack builder detects Laravel, installs PHP and Node dependencies, and runs `npm run build`.
2. In the service's **Variables**, add everything from `.env.example`, plus:
   - `APP_ENV=production`, `APP_DEBUG=false`
   - `APP_KEY`: paste the output of `php artisan key:generate --show`
   - `APP_URL`: your Railway URL, e.g. `https://folio-production.up.railway.app`
3. Under **Settings → Networking**, click *Generate Domain* to get the public URL.
4. `railway.json` runs `php artisan migrate --force` before each deploy and checks `/up` for health.

## Tests

```bash
php artisan test
```

`tests/Feature/PortfolioFlowTest.php` covers the full required flow (create, save each section, choose template, generate, preview, every template rendering, delete) plus access control between users.

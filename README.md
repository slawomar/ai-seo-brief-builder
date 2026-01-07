# AI SEO Brief Builder (Laravel + Inertia React)

A reference project showcasing a clean Laravel architecture with Inertia (React), Tailwind, and queued background processing.
The app lets you create a Project, add Keywords, and generate an SEO content brief asynchronously (Job + progress + result JSON).

## Tech stack
- Laravel (PHP 8.2+)
- Inertia.js + React
- Tailwind CSS
- Vite
- Queue (database driver) + Jobs

## What this repo demonstrates
- RESTful controllers + validation + authorization-ready structure
- Domain service approach (generator separated from controllers)
- Queue job with retry/backoff and idempotency patterns
- Clean DB schema (projects / keywords / briefs)
- Developer-friendly onboarding (SQLite by default)

---

## Quickstart (SQLite - recommended)

### Requirements
- PHP 8.2+
- Composer
- Node 22+
- SQLite enabled in PHP (`pdo_sqlite`)

### Setup
```bash
composer install
npm install

cp .env.example .env
php artisan key:generate
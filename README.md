# TPQ Management System

Laravel 13 project scaffolded for a clean, modular, secure, and mobile-friendly TPQ administration system.

## Stack

- Laravel 13
- Filament Admin Panel
- Tailwind CSS 4
- MariaDB
- Spatie Laravel Permission
- Spatie Laravel Activitylog

## Setup

```bash
composer install
npm install
php artisan key:generate
php artisan migrate --seed
npm run dev
```

Default local database settings are in `.env.example`:

```dotenv
DB_CONNECTION=mariadb
DB_DATABASE=tpq_management_system
DB_USERNAME=root
DB_PASSWORD=
```

Session and cache use file drivers by default so the welcome page and Filament assets can load before database migration is run.

The initial admin is created by `DatabaseSeeder` from:

```dotenv
ADMIN_NAME="Super Admin"
ADMIN_EMAIL=admin@tpq.test
ADMIN_PASSWORD=ChangeMe!234
```

Change the password before using the app beyond local development.

## Admin Panel

The Filament panel is available at:

```text
/admin
```

Only users with the `super_admin` role can access it.

## Structure

Business modules live under `app/Domain`:

- `Academics`
- `Attendance`
- `Finance`
- `People`
- `Shared`

Architecture notes are in `docs/architecture.md`.

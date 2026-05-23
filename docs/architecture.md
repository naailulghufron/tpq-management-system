# TPQ Management System Architecture

## Stack

- Laravel 13
- Filament Admin Panel
- Tailwind CSS 4 through Vite
- MariaDB through Laravel's `mariadb` connection
- Spatie Laravel Permission
- Spatie Laravel Activitylog

## Module Layout

Domain code is grouped under `app/Domain` so each business area can grow without mixing concerns:

- `Academics`: halaqah, kelas, jadwal, materi, penilaian.
- `Attendance`: presensi santri, ustadz, rekap kehadiran.
- `Finance`: SPP, donasi, transaksi, laporan kas.
- `People`: santri, wali, ustadz, staff.
- `Shared`: value objects, enums, shared domain helpers.

Keep Filament resources thin. Put business rules in domain actions/services, persistence in models/repositories when needed, and authorization through policies plus Spatie roles/permissions.

## Security Baseline

- Filament admin access is restricted to users with the `super_admin` role.
- Initial roles are seeded from `DatabaseSeeder`.
- Activity logging is enabled and the `User` model logs name and email changes only.
- MariaDB credentials and initial admin credentials are environment-driven.

Before production, change `ADMIN_PASSWORD`, set `APP_ENV=production`, set `APP_DEBUG=false`, configure HTTPS, and add granular permissions/policies per module.

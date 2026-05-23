<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $permissions = $this->permissions();

        foreach ($permissions as $permission) {
            Permission::query()->firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        foreach ($this->rolePermissions($permissions) as $role => $rolePermissions) {
            Role::query()
                ->firstOrCreate([
                    'name' => $role,
                    'guard_name' => 'web',
                ])
                ->syncPermissions(
                    Permission::query()
                        ->whereIn('name', $rolePermissions)
                        ->where('guard_name', 'web')
                        ->get(),
                );
        }

        foreach ($this->settings() as $setting) {
            Setting::query()->firstOrCreate(
                ['group' => $setting['group'], 'key' => $setting['key']],
                [
                    'value' => $setting['value'],
                    'type' => $setting['type'],
                    'description' => $setting['description'],
                    'is_public' => $setting['is_public'],
                ],
            );
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    private function permissions(): array
    {
        return [
            'students.view',
            'students.create',
            'students.update',
            'students.delete',
            'teachers.view',
            'teachers.create',
            'teachers.update',
            'teachers.delete',
            'programs.view',
            'programs.create',
            'programs.update',
            'programs.delete',
            'attendance.view',
            'attendance.create',
            'attendance.update',
            'attendance.delete',
            'finance.view',
            'finance.create',
            'finance.update',
            'finance.approve',
            'finance.cancel',
            'savings.view',
            'savings.deposit',
            'savings.withdraw',
            'savings.approve',
            'savings.cancel',
            'blog.view',
            'blog.create',
            'blog.update',
            'blog.publish',
            'blog.delete',
            'reports.view',
            'reports.export',
            'users.view',
            'users.create',
            'users.update',
            'users.delete',
            'roles.view',
            'roles.create',
            'roles.update',
            'roles.delete',
            'permissions.view',
            'permissions.create',
            'permissions.update',
            'permissions.delete',
            'settings.manage',
            'activity_logs.view',
        ];
    }

    private function rolePermissions(array $permissions): array
    {
        return [
            'Super Admin' => $permissions,
            'Kepala TPQ' => [
                'students.view',
                'students.create',
                'students.update',
                'teachers.view',
                'teachers.create',
                'teachers.update',
                'programs.view',
                'programs.create',
                'programs.update',
                'attendance.view',
                'attendance.create',
                'attendance.update',
                'finance.view',
                'savings.view',
                'blog.view',
                'reports.view',
                'reports.export',
                'settings.manage',
                'activity_logs.view',
            ],
            'Bendahara' => [
                'students.view',
                'finance.view',
                'finance.create',
                'finance.update',
                'finance.approve',
                'finance.cancel',
                'savings.view',
                'savings.deposit',
                'savings.withdraw',
                'savings.approve',
                'savings.cancel',
                'reports.view',
                'reports.export',
                'activity_logs.view',
            ],
            'Guru' => [
                'students.view',
                'programs.view',
                'attendance.view',
                'attendance.create',
                'attendance.update',
                'reports.view',
            ],
            'Wali Santri' => [
                'students.view',
                'attendance.view',
                'savings.view',
            ],
            'Admin Website' => [
                'blog.view',
                'blog.create',
                'blog.update',
                'blog.publish',
                'blog.delete',
            ],
            'Auditor' => [
                'students.view',
                'teachers.view',
                'programs.view',
                'attendance.view',
                'finance.view',
                'savings.view',
                'reports.view',
                'reports.export',
                'activity_logs.view',
            ],
        ];
    }

    private function settings(): array
    {
        return [
            ['group' => 'general', 'key' => 'tpq_name', 'value' => 'TPQ Management System', 'type' => 'text', 'description' => 'Nama TPQ.', 'is_public' => true],
            ['group' => 'general', 'key' => 'tpq_address', 'value' => null, 'type' => 'textarea', 'description' => 'Alamat TPQ.', 'is_public' => true],
            ['group' => 'general', 'key' => 'tpq_phone', 'value' => null, 'type' => 'text', 'description' => 'Nomor telepon TPQ.', 'is_public' => true],
            ['group' => 'general', 'key' => 'tpq_email', 'value' => null, 'type' => 'email', 'description' => 'Email TPQ.', 'is_public' => true],
            ['group' => 'general', 'key' => 'tpq_logo', 'value' => null, 'type' => 'text', 'description' => 'Path logo publik.', 'is_public' => true],
            ['group' => 'general', 'key' => 'tpq_favicon', 'value' => null, 'type' => 'text', 'description' => 'Path favicon publik.', 'is_public' => true],
            ['group' => 'general', 'key' => 'tpq_slogan', 'value' => null, 'type' => 'text', 'description' => 'Slogan TPQ.', 'is_public' => true],
            ['group' => 'general', 'key' => 'footer_text', 'value' => null, 'type' => 'textarea', 'description' => 'Teks footer website.', 'is_public' => true],
            ['group' => 'finance', 'key' => 'default_currency', 'value' => 'IDR', 'type' => 'text', 'description' => 'Mata uang default.', 'is_public' => false],
            ['group' => 'education', 'key' => 'active_academic_year', 'value' => null, 'type' => 'text', 'description' => 'Tahun ajaran aktif.', 'is_public' => false],
            ['group' => 'system', 'key' => 'app_timezone', 'value' => 'Asia/Jakarta', 'type' => 'text', 'description' => 'Timezone aplikasi.', 'is_public' => false],
        ];
    }
}

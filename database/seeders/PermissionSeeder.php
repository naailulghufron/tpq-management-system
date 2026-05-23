<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    /**
     * Seed application permissions.
     */
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $permissions = [
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
            'settings.manage',
            'users.manage',
            'roles.manage',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission);
        }

        Role::findOrCreate('Super Admin')->syncPermissions($permissions);

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}

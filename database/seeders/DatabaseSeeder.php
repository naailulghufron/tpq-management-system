<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $roles = [
            'Super Admin',
            'Kepala TPQ',
            'Bendahara',
            'Guru',
            'Wali Santri',
            'Admin Website',
            'Auditor',
        ];

        foreach ($roles as $role) {
            Role::findOrCreate($role);
        }

        $this->call(PermissionSeeder::class);

        $admin = User::query()->firstOrCreate(
            ['email' => env('ADMIN_EMAIL', 'admin@tpq.test')],
            [
                'name' => env('ADMIN_NAME', 'Super Admin'),
                'password' => env('ADMIN_PASSWORD', 'ChangeMe!234'),
            ],
        );

        $admin->assignRole('Super Admin');
    }
}

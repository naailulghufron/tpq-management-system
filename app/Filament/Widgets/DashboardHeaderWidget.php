<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class DashboardHeaderWidget extends Widget
{
    protected string $view = 'filament.widgets.dashboard-header';

    protected int|string|array $columnSpan = 'full';

    protected static ?int $sort = 1;

    public function roleSummary(): string
    {
        return auth()->user()?->roles?->pluck('name')->join(', ') ?: 'User';
    }

    public function accessSummary(): string
    {
        $permissions = auth()->user()?->getAllPermissions()->pluck('name') ?? collect();

        return collect([
            'Master Data' => $permissions->contains(fn (string $permission): bool => str_starts_with($permission, 'students.') || str_starts_with($permission, 'teachers.')),
            'Pendidikan' => $permissions->contains(fn (string $permission): bool => str_starts_with($permission, 'programs.')),
            'Absensi' => $permissions->contains(fn (string $permission): bool => str_starts_with($permission, 'attendance.')),
            'Keuangan' => $permissions->contains(fn (string $permission): bool => str_starts_with($permission, 'finance.')),
            'Tabungan' => $permissions->contains(fn (string $permission): bool => str_starts_with($permission, 'savings.')),
            'Website' => $permissions->contains(fn (string $permission): bool => str_starts_with($permission, 'blog.')),
            'Audit' => $permissions->contains('activity_logs.view'),
        ])
            ->filter()
            ->keys()
            ->join(', ') ?: 'Akses terbatas';
    }
}

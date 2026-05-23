<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\DashboardHeaderWidget;
use App\Filament\Widgets\EducationProgressChart;
use App\Filament\Widgets\OverviewStatsWidget;
use App\Filament\Widgets\RecentActivitiesWidget;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $title = 'Dashboard TPQ';

    public function getHeading(): string
    {
        return 'Dashboard TPQ';
    }

    public function getSubheading(): ?string
    {
        $roles = auth()->user()?->roles?->pluck('name')->join(', ');

        return $roles
            ? "Ringkasan operasional sesuai akses: {$roles}."
            : 'Ringkasan operasional sesuai permission pengguna.';
    }

    public function getColumns(): int|array
    {
        return [
            'default' => 1,
            'md' => 2,
            'xl' => 6,
        ];
    }

    public function getWidgets(): array
    {
        $widgets = [
            DashboardHeaderWidget::class,
        ];

        if ($this->canViewOperationalStats()) {
            $widgets[] = OverviewStatsWidget::class;
        }

        if (auth()->user()?->can('programs.view')) {
            $widgets[] = EducationProgressChart::class;
        }

        if (auth()->user()?->can('activity_logs.view')) {
            $widgets[] = RecentActivitiesWidget::class;
        }

        return $widgets;
    }

    private function canViewOperationalStats(): bool
    {
        return auth()->user()?->canAny([
            'students.view',
            'teachers.view',
            'programs.view',
            'attendance.view',
            'finance.view',
            'savings.view',
        ]) ?? false;
    }
}

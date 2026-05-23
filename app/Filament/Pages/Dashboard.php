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
        return 'Ringkasan operasional pendidikan, absensi, keuangan, dan aktivitas terbaru.';
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
        return [
            DashboardHeaderWidget::class,
            OverviewStatsWidget::class,
            EducationProgressChart::class,
            RecentActivitiesWidget::class,
        ];
    }
}

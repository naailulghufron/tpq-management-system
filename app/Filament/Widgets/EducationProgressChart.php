<?php

namespace App\Filament\Widgets;

use App\Models\StudentProgress;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Schema;

class EducationProgressChart extends ChartWidget
{
    protected ?string $heading = 'Progress pendidikan';

    protected ?string $description = 'Distribusi capaian materi santri.';

    protected string $color = 'success';

    protected int|string|array $columnSpan = [
        'default' => 'full',
        'xl' => 3,
    ];

    protected static ?int $sort = 3;

    public static function canView(): bool
    {
        return auth()->user()?->can('programs.view') ?? false;
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getData(): array
    {
        if (! Schema::hasTable('student_progress')) {
            return $this->emptyData();
        }

        $completed = StudentProgress::query()->where('status', 'active')->where('progress_percent', '>=', 100)->count();
        $inProgress = StudentProgress::query()->where('status', 'active')->whereBetween('progress_percent', [1, 99])->count();
        $notStarted = StudentProgress::query()->where('status', 'active')->where('progress_percent', 0)->count();

        return [
            'datasets' => [
                [
                    'data' => [$completed, $inProgress, $notStarted],
                    'backgroundColor' => ['#059669', '#d6a84f', '#e5e7eb'],
                    'borderColor' => '#ffffff',
                ],
            ],
            'labels' => ['Tuntas', 'Berjalan', 'Belum mulai'],
        ];
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'position' => 'bottom',
                ],
            ],
            'responsive' => true,
            'maintainAspectRatio' => false,
        ];
    }

    private function emptyData(): array
    {
        return [
            'datasets' => [
                [
                    'data' => [0, 0, 0],
                    'backgroundColor' => ['#059669', '#d6a84f', '#e5e7eb'],
                ],
            ],
            'labels' => ['Tuntas', 'Berjalan', 'Belum mulai'],
        ];
    }
}

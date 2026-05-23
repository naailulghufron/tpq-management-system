<?php

namespace App\Filament\Pages;

use App\Filament\Pages\Concerns\ManagesSettings;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class EducationSettings extends Page
{
    use ManagesSettings;

    protected string $view = 'filament.pages.settings-form';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBookOpen;

    protected static string|UnitEnum|null $navigationGroup = 'Settings';

    protected static ?string $navigationLabel = 'Pengaturan Pendidikan';

    protected static ?string $title = 'Pengaturan Pendidikan';

    protected static ?int $navigationSort = 105;

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    protected function settingsGroup(): string
    {
        return 'education';
    }

    public function settingFields(): array
    {
        return [
            'minimum_score' => ['label' => 'Nilai Minimum Kelulusan', 'type' => 'number', 'default' => 70],
            'progress_pass_percent' => ['label' => 'Persentase Tuntas', 'type' => 'number', 'default' => 100],
            'enable_program_levels' => ['label' => 'Aktifkan Level Program', 'type' => 'boolean', 'default' => true],
            'enable_material_tracking' => ['label' => 'Aktifkan Tracking Materi', 'type' => 'boolean', 'default' => true],
            'attendance_late_tolerance_minutes' => ['label' => 'Toleransi Terlambat Menit', 'type' => 'number', 'default' => 10],
            'assessment_notes' => ['label' => 'Catatan Penilaian', 'type' => 'textarea'],
        ];
    }
}

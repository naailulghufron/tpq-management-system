<?php

namespace App\Filament\Pages;

use App\Filament\Pages\Concerns\ManagesSettings;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class AcademicYearSettings extends Page
{
    use ManagesSettings;

    protected string $view = 'filament.pages.settings-form';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendarDays;

    protected static string|UnitEnum|null $navigationGroup = 'Settings';

    protected static ?string $navigationLabel = 'Tahun Ajaran';

    protected static ?string $title = 'Pengaturan Tahun Ajaran';

    protected static ?int $navigationSort = 103;

    protected function settingsGroup(): string
    {
        return 'academic_year';
    }

    public function settingFields(): array
    {
        return [
            'current_academic_year_id' => ['label' => 'ID Tahun Ajaran Aktif', 'type' => 'number'],
            'registration_open' => ['label' => 'Pendaftaran Dibuka', 'type' => 'boolean', 'default' => true],
            'registration_start_date' => ['label' => 'Tanggal Mulai Pendaftaran', 'type' => 'date'],
            'registration_end_date' => ['label' => 'Tanggal Akhir Pendaftaran', 'type' => 'date'],
            'semester_name' => ['label' => 'Nama Semester/Periode', 'type' => 'text'],
            'notes' => ['label' => 'Catatan Tahun Ajaran', 'type' => 'textarea'],
        ];
    }
}

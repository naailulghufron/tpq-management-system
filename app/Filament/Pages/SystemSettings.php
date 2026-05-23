<?php

namespace App\Filament\Pages;

use App\Filament\Pages\Concerns\ManagesSettings;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class SystemSettings extends Page
{
    use ManagesSettings;

    protected string $view = 'filament.pages.settings-form';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static string|UnitEnum|null $navigationGroup = 'Settings';

    protected static ?string $navigationLabel = 'Pengaturan Sistem';

    protected static ?string $title = 'Pengaturan Sistem';

    protected static ?int $navigationSort = 106;

    protected function settingsGroup(): string
    {
        return 'system';
    }

    public function settingFields(): array
    {
        return [
            'timezone' => ['label' => 'Timezone', 'type' => 'text', 'default' => 'Asia/Jakarta'],
            'date_format' => ['label' => 'Format Tanggal', 'type' => 'text', 'default' => 'd M Y'],
            'maintenance_message' => ['label' => 'Pesan Maintenance', 'type' => 'textarea'],
            'enable_public_registration' => ['label' => 'Aktifkan Pendaftaran Publik', 'type' => 'boolean', 'default' => true],
            'enable_activity_logging' => ['label' => 'Aktifkan Activity Log', 'type' => 'boolean', 'default' => true],
            'session_lifetime_minutes' => ['label' => 'Durasi Session Menit', 'type' => 'number', 'default' => 120],
        ];
    }
}

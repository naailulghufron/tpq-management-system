<?php

namespace App\Filament\Pages;

use App\Filament\Pages\Concerns\ManagesSettings;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class BrandingSettings extends Page
{
    use ManagesSettings;

    protected string $view = 'filament.pages.settings-form';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSwatch;

    protected static string|UnitEnum|null $navigationGroup = 'Settings';

    protected static ?string $navigationLabel = 'Branding';

    protected static ?string $title = 'Branding';

    protected static ?int $navigationSort = 102;

    protected function settingsGroup(): string
    {
        return 'branding';
    }

    public function settingFields(): array
    {
        return [
            'site_title' => ['label' => 'Judul Website', 'type' => 'text', 'default' => 'TPQ Management System'],
            'tagline' => ['label' => 'Tagline', 'type' => 'text'],
            'logo_path' => ['label' => 'Path Logo', 'type' => 'text', 'description' => 'Gunakan path file publik, misalnya storage/logo.png.'],
            'favicon_path' => ['label' => 'Path Favicon', 'type' => 'text'],
            'primary_color' => ['label' => 'Warna Utama', 'type' => 'color', 'default' => '#047857'],
            'accent_color' => ['label' => 'Warna Aksen', 'type' => 'color', 'default' => '#d6a84f'],
        ];
    }
}

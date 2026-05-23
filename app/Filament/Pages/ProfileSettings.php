<?php

namespace App\Filament\Pages;

use App\Filament\Pages\Concerns\ManagesSettings;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class ProfileSettings extends Page
{
    use ManagesSettings;

    protected string $view = 'filament.pages.settings-form';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingLibrary;

    protected static string|UnitEnum|null $navigationGroup = 'Settings';

    protected static ?string $navigationLabel = 'Profil TPQ';

    protected static ?string $title = 'Profil TPQ';

    protected static ?int $navigationSort = 101;

    protected function settingsGroup(): string
    {
        return 'profile';
    }

    public function settingFields(): array
    {
        return [
            'institution_name' => ['label' => 'Nama TPQ', 'type' => 'text', 'default' => 'TPQ Management System'],
            'legal_name' => ['label' => 'Nama Legal/Yayasan', 'type' => 'text'],
            'principal_name' => ['label' => 'Nama Kepala TPQ', 'type' => 'text'],
            'phone' => ['label' => 'Nomor Telepon', 'type' => 'text'],
            'email' => ['label' => 'Email', 'type' => 'email'],
            'address' => ['label' => 'Alamat', 'type' => 'textarea'],
            'vision' => ['label' => 'Visi', 'type' => 'textarea'],
            'mission' => ['label' => 'Misi', 'type' => 'textarea'],
        ];
    }
}

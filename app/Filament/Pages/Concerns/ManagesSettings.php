<?php

namespace App\Filament\Pages\Concerns;

use App\Models\Setting;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Schema;

trait ManagesSettings
{
    public array $settings = [];

    public function mount(): void
    {
        $this->settings = collect($this->settingFields())
            ->mapWithKeys(fn (array $field, string $key): array => [$key => $this->settingValue($key, $field)])
            ->all();
    }

    public static function canAccess(): bool
    {
        return auth()->user()?->can('settings.manage') ?? false;
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::canAccess();
    }

    public function save(): void
    {
        abort_unless(auth()->user()?->can('settings.manage'), 403);

        foreach ($this->settingFields() as $key => $field) {
            Setting::query()->updateOrCreate(
                ['group' => $this->settingsGroup(), 'key' => $key],
                [
                    'value' => $this->normalizeSettingValue($this->settings[$key] ?? null, $field['type'] ?? 'text'),
                    'type' => $field['type'] ?? 'text',
                    'description' => $field['description'] ?? null,
                ],
            );
        }

        Notification::make()
            ->title('Pengaturan berhasil disimpan')
            ->success()
            ->send();
    }

    abstract protected function settingsGroup(): string;

    abstract public function settingFields(): array;

    private function settingValue(string $key, array $field): mixed
    {
        if (! Schema::hasTable('settings')) {
            return $field['default'] ?? null;
        }

        $value = Setting::query()
            ->where('group', $this->settingsGroup())
            ->where('key', $key)
            ->value('value');

        if ($value === null) {
            return $field['default'] ?? null;
        }

        return match ($field['type'] ?? 'text') {
            'boolean' => filter_var($value, FILTER_VALIDATE_BOOLEAN),
            'number' => is_numeric($value) ? (float) $value : 0,
            default => $value,
        };
    }

    private function normalizeSettingValue(mixed $value, string $type): ?string
    {
        if ($value === null) {
            return null;
        }

        return match ($type) {
            'boolean' => $value ? '1' : '0',
            default => (string) $value,
        };
    }
}

<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use UnitEnum;

class RolePermissionMatrix extends Page
{
    protected string $view = 'filament.pages.role-permission-matrix';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTableCells;

    protected static string|UnitEnum|null $navigationGroup = 'User Management';

    protected static ?string $navigationLabel = 'Role Permission Matrix';

    protected static ?string $title = 'Role Permission Matrix';

    protected static ?int $navigationSort = 205;

    public array $matrix = [];

    public function mount(): void
    {
        $this->matrix = Role::query()
            ->with('permissions')
            ->orderBy('name')
            ->get()
            ->mapWithKeys(fn (Role $role): array => [
                $role->id => $role->permissions->pluck('id')->flip()->map(fn (): bool => true)->all(),
            ])
            ->all();
    }

    public static function canAccess(): bool
    {
        return auth()->user()?->can('roles.view') ?? false;
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::canAccess();
    }

    public function roles()
    {
        return Role::query()->orderBy('name')->get();
    }

    public function permissionGroups()
    {
        return Permission::query()
            ->orderBy('name')
            ->get()
            ->groupBy(fn (Permission $permission): string => str($permission->name)->before('.')->toString());
    }

    public function save(): void
    {
        abort_unless(auth()->user()?->can('roles.update'), 403);

        foreach ($this->roles() as $role) {
            $permissionIds = collect($this->matrix[$role->id] ?? [])
                ->filter()
                ->keys()
                ->all();

            $role->syncPermissions(
                Permission::query()
                    ->whereIn('id', $permissionIds)
                    ->get(),
            );
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        activity('roles')
            ->causedBy(auth()->user())
            ->withProperties(['matrix' => $this->matrix])
            ->event('updated')
            ->log('change permission matrix');

        Notification::make()
            ->title('Matrix permission berhasil disimpan')
            ->success()
            ->send();
    }
}

<?php

namespace App\Filament\Resources\Roles\Pages;

use App\Filament\Resources\Roles\RoleResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditRole extends EditRecord
{
    protected static string $resource = RoleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        activity('roles')
            ->causedBy(auth()->user())
            ->performedOn($this->record)
            ->withProperties([
                'permissions' => $this->record->permissions()->pluck('name')->all(),
            ])
            ->event('updated')
            ->log('change permission');
    }
}

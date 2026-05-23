<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function afterCreate(): void
    {
        activity('users')
            ->causedBy(auth()->user())
            ->performedOn($this->record)
            ->withProperties([
                'roles' => $this->record->roles()->pluck('name')->all(),
            ])
            ->event('created')
            ->log('create user');
    }
}

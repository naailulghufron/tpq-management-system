<?php

namespace App\Filament\Resources\StudentSavingsAccounts\Pages;

use App\Filament\Resources\StudentSavingsAccounts\StudentSavingsAccountResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditStudentSavingsAccount extends EditRecord
{
    protected static string $resource = StudentSavingsAccountResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}

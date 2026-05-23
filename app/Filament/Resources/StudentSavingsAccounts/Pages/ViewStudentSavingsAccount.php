<?php

namespace App\Filament\Resources\StudentSavingsAccounts\Pages;

use App\Filament\Resources\StudentSavingsAccounts\StudentSavingsAccountResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewStudentSavingsAccount extends ViewRecord
{
    protected static string $resource = StudentSavingsAccountResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}

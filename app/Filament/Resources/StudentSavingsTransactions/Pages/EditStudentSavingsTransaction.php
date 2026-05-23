<?php

namespace App\Filament\Resources\StudentSavingsTransactions\Pages;

use App\Filament\Resources\StudentSavingsTransactions\StudentSavingsTransactionResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditStudentSavingsTransaction extends EditRecord
{
    protected static string $resource = StudentSavingsTransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}

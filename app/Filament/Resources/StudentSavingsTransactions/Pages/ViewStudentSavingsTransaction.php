<?php

namespace App\Filament\Resources\StudentSavingsTransactions\Pages;

use App\Filament\Resources\StudentSavingsTransactions\StudentSavingsTransactionResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewStudentSavingsTransaction extends ViewRecord
{
    protected static string $resource = StudentSavingsTransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}

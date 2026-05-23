<?php

namespace App\Filament\Resources\StudentSavingsTransactions\Pages;

use App\Filament\Resources\StudentSavingsTransactions\StudentSavingsTransactionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListStudentSavingsTransactions extends ListRecords
{
    protected static string $resource = StudentSavingsTransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

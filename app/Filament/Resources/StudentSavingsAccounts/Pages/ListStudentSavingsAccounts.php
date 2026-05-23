<?php

namespace App\Filament\Resources\StudentSavingsAccounts\Pages;

use App\Filament\Resources\StudentSavingsAccounts\StudentSavingsAccountResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListStudentSavingsAccounts extends ListRecords
{
    protected static string $resource = StudentSavingsAccountResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

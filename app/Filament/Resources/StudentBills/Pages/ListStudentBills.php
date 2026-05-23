<?php

namespace App\Filament\Resources\StudentBills\Pages;

use App\Filament\Resources\StudentBills\StudentBillResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListStudentBills extends ListRecords
{
    protected static string $resource = StudentBillResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

<?php

namespace App\Filament\Resources\StudentBills\Pages;

use App\Filament\Resources\StudentBills\StudentBillResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewStudentBill extends ViewRecord
{
    protected static string $resource = StudentBillResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}

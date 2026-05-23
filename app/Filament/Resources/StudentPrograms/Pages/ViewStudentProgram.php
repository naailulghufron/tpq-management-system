<?php

namespace App\Filament\Resources\StudentPrograms\Pages;

use App\Filament\Resources\StudentPrograms\StudentProgramResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewStudentProgram extends ViewRecord
{
    protected static string $resource = StudentProgramResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}

<?php

namespace App\Filament\Resources\StudentPrograms\Pages;

use App\Filament\Resources\StudentPrograms\StudentProgramResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListStudentPrograms extends ListRecords
{
    protected static string $resource = StudentProgramResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

<?php

namespace App\Filament\Resources\ProgramLevels\Pages;

use App\Filament\Resources\ProgramLevels\ProgramLevelResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListProgramLevels extends ListRecords
{
    protected static string $resource = ProgramLevelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

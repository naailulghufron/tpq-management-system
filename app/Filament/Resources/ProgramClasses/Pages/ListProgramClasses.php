<?php

namespace App\Filament\Resources\ProgramClasses\Pages;

use App\Filament\Resources\ProgramClasses\ProgramClassResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListProgramClasses extends ListRecords
{
    protected static string $resource = ProgramClassResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

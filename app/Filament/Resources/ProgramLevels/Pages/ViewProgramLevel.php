<?php

namespace App\Filament\Resources\ProgramLevels\Pages;

use App\Filament\Resources\ProgramLevels\ProgramLevelResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewProgramLevel extends ViewRecord
{
    protected static string $resource = ProgramLevelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}

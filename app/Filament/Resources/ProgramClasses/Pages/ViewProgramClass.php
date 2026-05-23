<?php

namespace App\Filament\Resources\ProgramClasses\Pages;

use App\Filament\Resources\ProgramClasses\ProgramClassResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewProgramClass extends ViewRecord
{
    protected static string $resource = ProgramClassResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}

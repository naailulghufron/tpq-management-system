<?php

namespace App\Filament\Resources\ProgramLevels\Pages;

use App\Filament\Resources\ProgramLevels\ProgramLevelResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditProgramLevel extends EditRecord
{
    protected static string $resource = ProgramLevelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}

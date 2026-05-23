<?php

namespace App\Filament\Resources\ProgramClasses\Pages;

use App\Filament\Resources\ProgramClasses\ProgramClassResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditProgramClass extends EditRecord
{
    protected static string $resource = ProgramClassResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}

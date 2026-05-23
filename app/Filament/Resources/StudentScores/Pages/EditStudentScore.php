<?php

namespace App\Filament\Resources\StudentScores\Pages;

use App\Filament\Resources\StudentScores\StudentScoreResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditStudentScore extends EditRecord
{
    protected static string $resource = StudentScoreResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}

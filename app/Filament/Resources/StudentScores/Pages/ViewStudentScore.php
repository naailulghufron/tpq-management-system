<?php

namespace App\Filament\Resources\StudentScores\Pages;

use App\Filament\Resources\StudentScores\StudentScoreResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewStudentScore extends ViewRecord
{
    protected static string $resource = StudentScoreResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}

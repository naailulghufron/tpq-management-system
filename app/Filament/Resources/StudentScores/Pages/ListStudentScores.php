<?php

namespace App\Filament\Resources\StudentScores\Pages;

use App\Filament\Resources\StudentScores\StudentScoreResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListStudentScores extends ListRecords
{
    protected static string $resource = StudentScoreResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

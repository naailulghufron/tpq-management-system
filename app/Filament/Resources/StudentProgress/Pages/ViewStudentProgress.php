<?php

namespace App\Filament\Resources\StudentProgress\Pages;

use App\Filament\Resources\StudentProgress\StudentProgressResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewStudentProgress extends ViewRecord
{
    protected static string $resource = StudentProgressResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}

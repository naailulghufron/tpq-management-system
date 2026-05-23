<?php

namespace App\Filament\Resources\TeacherAttendances\Pages;

use App\Filament\Resources\TeacherAttendances\TeacherAttendanceResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewTeacherAttendance extends ViewRecord
{
    protected static string $resource = TeacherAttendanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}

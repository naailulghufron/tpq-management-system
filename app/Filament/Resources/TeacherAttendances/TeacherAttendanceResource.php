<?php

namespace App\Filament\Resources\TeacherAttendances;

use App\Filament\Resources\TeacherAttendances\Pages\CreateTeacherAttendance;
use App\Filament\Resources\TeacherAttendances\Pages\EditTeacherAttendance;
use App\Filament\Resources\TeacherAttendances\Pages\ListTeacherAttendances;
use App\Filament\Resources\TeacherAttendances\Pages\ViewTeacherAttendance;
use App\Filament\Support\ResourceDefaults;
use App\Models\TeacherAttendance;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TeacherAttendanceResource extends Resource
{
    protected static ?string $model = TeacherAttendance::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function getNavigationGroup(): string|\UnitEnum|null
    {
        return ResourceDefaults::group(static::$model);
    }

    public static function getRecordTitleAttribute(): ?string
    {
        return ResourceDefaults::titleAttribute(static::$model);
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components(ResourceDefaults::formComponents(static::$model));
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components(ResourceDefaults::infolistComponents(static::$model));
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(ResourceDefaults::tableColumns(static::$model))
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTeacherAttendances::route('/'),
            'create' => CreateTeacherAttendance::route('/create'),
            'view' => ViewTeacherAttendance::route('/{record}'),
            'edit' => EditTeacherAttendance::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}

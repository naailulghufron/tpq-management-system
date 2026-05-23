<?php

namespace App\Filament\Resources\ProgramClasses;

use App\Filament\Resources\ProgramClasses\Pages\CreateProgramClass;
use App\Filament\Resources\ProgramClasses\Pages\EditProgramClass;
use App\Filament\Resources\ProgramClasses\Pages\ListProgramClasses;
use App\Filament\Resources\ProgramClasses\Pages\ViewProgramClass;
use App\Filament\Support\ResourceDefaults;
use App\Models\ProgramClass;
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

class ProgramClassResource extends Resource
{
    protected static ?string $model = ProgramClass::class;

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
            'index' => ListProgramClasses::route('/'),
            'create' => CreateProgramClass::route('/create'),
            'view' => ViewProgramClass::route('/{record}'),
            'edit' => EditProgramClass::route('/{record}/edit'),
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

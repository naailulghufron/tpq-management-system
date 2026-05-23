<?php

namespace App\Filament\Resources\Settings;

use App\Filament\Resources\Settings\Pages\CreateSetting;
use App\Filament\Resources\Settings\Pages\EditSetting;
use App\Filament\Resources\Settings\Pages\ListSettings;
use App\Filament\Resources\Settings\Pages\ViewSetting;
use App\Models\Setting;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use UnitEnum;

class SettingResource extends Resource
{
    protected static ?string $model = Setting::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedAdjustmentsHorizontal;

    protected static string|UnitEnum|null $navigationGroup = 'Settings';

    protected static ?string $navigationLabel = 'Settings Data';

    protected static ?int $navigationSort = 100;

    public static function canViewAny(): bool
    {
        return auth()->user()?->can('settings.manage') ?? false;
    }

    public static function canCreate(): bool
    {
        return auth()->user()?->can('settings.manage') ?? false;
    }

    public static function canEdit($record): bool
    {
        return auth()->user()?->can('settings.manage') ?? false;
    }

    public static function canDelete($record): bool
    {
        return auth()->user()?->can('settings.manage') ?? false;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('group')
                    ->required()
                    ->maxLength(255),
                TextInput::make('key')
                    ->required()
                    ->maxLength(255),
                Select::make('type')
                    ->options([
                        'text' => 'Text',
                        'textarea' => 'Textarea',
                        'number' => 'Number',
                        'boolean' => 'Boolean',
                        'email' => 'Email',
                        'url' => 'URL',
                        'color' => 'Color',
                        'date' => 'Date',
                    ])
                    ->default('text')
                    ->required(),
                Toggle::make('is_public')
                    ->label('Public')
                    ->default(false),
                Textarea::make('value')
                    ->rows(4)
                    ->columnSpanFull(),
                Textarea::make('description')
                    ->rows(3)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('group')->badge()->searchable()->sortable(),
                TextColumn::make('key')->searchable()->sortable(),
                TextColumn::make('value')->limit(50)->searchable(),
                TextColumn::make('type')->badge()->sortable(),
                IconColumn::make('is_public')->boolean()->sortable(),
                TextColumn::make('updated_at')->dateTime('d M Y H:i')->sortable(),
            ])
            ->filters([
                SelectFilter::make('group')
                    ->options(fn (): array => Setting::query()
                        ->distinct()
                        ->orderBy('group')
                        ->pluck('group', 'group')
                        ->all()),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSettings::route('/'),
            'create' => CreateSetting::route('/create'),
            'view' => ViewSetting::route('/{record}'),
            'edit' => EditSetting::route('/{record}/edit'),
        ];
    }
}

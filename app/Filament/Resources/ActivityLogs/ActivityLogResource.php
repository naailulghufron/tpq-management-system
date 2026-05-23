<?php

namespace App\Filament\Resources\ActivityLogs;

use App\Filament\Resources\ActivityLogs\Pages\ListActivityLogs;
use App\Filament\Resources\ActivityLogs\Pages\ViewActivityLog;
use BackedEnum;
use Filament\Actions\ViewAction;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Activitylog\Models\Activity;
use UnitEnum;

class ActivityLogResource extends Resource
{
    protected static ?string $model = Activity::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClock;

    protected static string|UnitEnum|null $navigationGroup = 'User Management';

    protected static ?string $navigationLabel = 'Activity Logs';

    protected static ?int $navigationSort = 204;

    public static function canViewAny(): bool
    {
        return auth()->user()?->can('activity_logs.view') ?? false;
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit($record): bool
    {
        return false;
    }

    public static function canDelete($record): bool
    {
        return false;
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('description')->label('Aktivitas'),
                TextEntry::make('log_name')->label('Modul')->badge(),
                TextEntry::make('event')->badge(),
                TextEntry::make('causer.name')->label('User')->placeholder('-'),
                TextEntry::make('subject_type')->label('Subject Type')->placeholder('-'),
                TextEntry::make('subject_id')->label('Subject ID')->placeholder('-'),
                TextEntry::make('properties')->formatStateUsing(fn ($state): string => json_encode($state, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE))->columnSpanFull(),
                TextEntry::make('created_at')->dateTime('d M Y H:i:s'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('description')->label('Aktivitas')->searchable()->wrap(),
                TextColumn::make('log_name')->label('Modul')->badge()->searchable(),
                TextColumn::make('event')->badge()->placeholder('-'),
                TextColumn::make('causer.name')->label('User')->placeholder('-')->searchable(),
                TextColumn::make('created_at')->label('Waktu')->dateTime('d M Y H:i')->sortable(),
            ])
            ->filters([
                SelectFilter::make('log_name')
                    ->label('Modul')
                    ->options(fn (): array => Activity::query()
                        ->whereNotNull('log_name')
                        ->distinct()
                        ->orderBy('log_name')
                        ->pluck('log_name', 'log_name')
                        ->all()),
                SelectFilter::make('event')
                    ->options(fn (): array => Activity::query()
                        ->whereNotNull('event')
                        ->distinct()
                        ->orderBy('event')
                        ->pluck('event', 'event')
                        ->all()),
            ])
            ->recordActions([
                ViewAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with('causer');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListActivityLogs::route('/'),
            'view' => ViewActivityLog::route('/{record}'),
        ];
    }
}

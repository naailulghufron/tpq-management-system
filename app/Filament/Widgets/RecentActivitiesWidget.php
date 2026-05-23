<?php

namespace App\Filament\Widgets;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;
use Spatie\Activitylog\Models\Activity;

class RecentActivitiesWidget extends TableWidget
{
    protected int|string|array $columnSpan = [
        'default' => 'full',
        'xl' => 3,
    ];

    protected static ?int $sort = 4;

    public static function canView(): bool
    {
        return auth()->user()?->can('activity_logs.view') ?? false;
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading('Aktivitas terbaru')
            ->description('Audit log ringkas untuk aksi penting.')
            ->query($this->activityQuery())
            ->columns([
                TextColumn::make('description')
                    ->label('Aktivitas')
                    ->searchable()
                    ->wrap(),
                TextColumn::make('log_name')
                    ->label('Modul')
                    ->badge()
                    ->color('success'),
                TextColumn::make('causer.name')
                    ->label('User')
                    ->placeholder('-'),
                TextColumn::make('created_at')
                    ->label('Waktu')
                    ->since()
                    ->sortable(),
            ])
            ->paginated(false)
            ->defaultSort('created_at', 'desc');
    }

    private function activityQuery(): Builder
    {
        if (! Schema::hasTable('activity_log')) {
            return Activity::query()->whereRaw('1 = 0');
        }

        return Activity::query()->latest()->limit(8);
    }
}

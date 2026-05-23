<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Hash;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            Action::make('resetPassword')
                ->label('Reset Password')
                ->icon('heroicon-o-key')
                ->color('warning')
                ->visible(fn (): bool => auth()->user()?->can('users.update') ?? false)
                ->schema([
                    TextInput::make('password')
                        ->label('Password Baru')
                        ->password()
                        ->revealable()
                        ->required()
                        ->minLength(8),
                ])
                ->action(function (array $data): void {
                    $this->record->forceFill([
                        'password' => Hash::make($data['password']),
                        'must_change_password' => true,
                    ])->save();

                    activity('users')
                        ->causedBy(auth()->user())
                        ->performedOn($this->record)
                        ->event('updated')
                        ->log('reset user password');

                    Notification::make()
                        ->title('Password berhasil direset')
                        ->success()
                        ->send();
                }),
            DeleteAction::make(),
            RestoreAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        activity('users')
            ->causedBy(auth()->user())
            ->performedOn($this->record)
            ->withProperties([
                'roles' => $this->record->roles()->pluck('name')->all(),
            ])
            ->event('updated')
            ->log('update user / assign role');
    }
}

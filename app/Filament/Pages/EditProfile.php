<?php

namespace App\Filament\Pages;

use App\Enums\DateTimeType;
use App\Models\Admin;
use App\Utils\DateTimeHelper;
use Filament\Forms;
use Filament\Facades\Filament;
use Filament\Forms\Components\BaseFileUpload;
use Filament\Notifications\Notification;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use DanHarrin\LivewireRateLimiting\WithRateLimiting;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class EditProfile extends FormPage
{
    use WithRateLimiting;

    protected static bool $shouldRegisterNavigation = false;
    protected static ?string $navigationIcon = 'heroicon-o-user';

    public function getTitle(): string
    {
        return __('app.titles.edit_profile');
    }

    protected function getFormModel(): ?Model
    {
        return $this->getUser();
    }

    protected function getFormData(): array
    {
        return $this->getUser()->only(['avatar', 'name', 'email']);
    }

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\Section::make()
                ->schema([
                    Forms\Components\Grid::make()
                        ->schema([
                            Forms\Components\FileUpload::make('avatar')
                                ->label(__('app.auth.fields.avatar'))
                                ->avatar()
                                ->deletable()
                                ->deleteUploadedFileUsing(fn($record) => $record->removeAvatar())
                                ->disk(fn($record) => $record->getAvatarDisk())
                                ->directory(fn($record) => $record->getAvatarDirectory())
                                ->getUploadedFileNameForStorageUsing(
                                    fn(
                                        BaseFileUpload $component,
                                        TemporaryUploadedFile $file
                                    ) => md5($component->getRecord()->username).'.'.$file->getClientOriginalExtension()
                                )
                                ->rules(['nullable', 'mimes:jpg,jpeg,png', 'max:1024']),
                        ]),

                    Forms\Components\TextInput::make('name')
                        ->label(__('app.auth.fields.name'))
                        ->maxLength(250)
                        ->required()
                        ->autofocus(),

                    Forms\Components\TextInput::make('email')
                        ->label(__('app.auth.fields.email'))
                        ->extraInputAttributes(['dir' => 'ltr'])
                        ->unique(ignoreRecord: true)
                        ->maxLength(250)
                        ->email()
                        ->required(),

                    Forms\Components\Grid::make(1)
                        ->schema([
                            Forms\Components\TextInput::make('current_password')
                                ->label(__('app.auth.fields.current_password'))
                                ->password()
                                ->required()
                                ->autocomplete('current-password'),
                        ]),

                    Forms\Components\TextInput::make('password')
                        ->label(__('app.auth.fields.new_password'))
                        ->helperText(__('app.auth.helps.if_wont_to_change_password'))
                        ->rule(Password::default()->mixedCase()->numbers()->symbols())
                        ->autocomplete('new-password')
                        ->dehydrated(fn($state): bool => filled($state))
                        ->dehydrateStateUsing(fn($state): string => Hash::make($state))
                        ->live(debounce: 500)
                        ->same('passwordConfirmation')
                        ->password(),

                    Forms\Components\TextInput::make('passwordConfirmation')
                        ->label(__('app.auth.fields.new_password_confirmation'))
                        ->helperText(__('app.auth.helps.if_wont_to_change_password'))
                        ->password()
                        ->dehydrated(false),
                ])
                ->columns(2),
        ];
    }

    protected function handleSave(array $data): void
    {
        try {
            $this->rateLimit(5, 120);
        } catch (TooManyRequestsException $exception) {
            $this->sendRateLimitNotification($exception);

            return;
        }

        if (! Hash::check($data['current_password'], $this->getUser()->password)) {
            $this->throwFailureValidationException();
        }

        unset($data['current_password']);

        $this->clearRateLimiter();
        $this->getUser()->update($data);

        if (request()->hasSession() && array_key_exists('password', $data)) {
            request()->session()->put([
                'password_hash_' . Filament::getAuthGuard() => $data['password'],
            ]);

            $this->data['current_password'] = null;
            $this->data['password'] = null;
            $this->data['passwordConfirmation'] = null;
        }

        $this->sendSuccessNotification(__('app.auth.notifications.profile_updated'));
    }

    protected function throwFailureValidationException(): void
    {
        throw ValidationException::withMessages([
            'data.current_password' => __('app.auth.validations.current_password_incorrect'),
        ]);
    }

    protected function sendRateLimitNotification(TooManyRequestsException $exception): void
    {
        $body = __('app.notifications.throttled_body_seconds', ['seconds' => $exception->secondsUntilAvailable]);

        if ($exception->secondsUntilAvailable > 60) {
            $minutes = floor($exception->secondsUntilAvailable / 60); // Get the whole minutes
            $remainingSeconds = $exception->secondsUntilAvailable % 60; // Get the remaining seconds

            $body = __('app.notifications.throttled_body_minutes_seconds', [
                'minutes' => $minutes,
                'seconds' => $remainingSeconds,
            ]);
        }

        Notification::make()
            ->title(__('app.notifications.throttled'))
            ->body($body)
            ->danger()
            ->send();
    }

    /** @return Admin $user
     * @throws \Exception
     */
    private function getUser(): Authenticatable & Model
    {
        $user = Filament::auth()->user();

        if (! $user instanceof Model) {
            throw new \Exception('The authenticated user object must be an Eloquent model to allow the profile page to update it.');
        }

        return $user;
    }
}

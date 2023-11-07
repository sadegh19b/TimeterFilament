<?php

namespace App\Filament\Pages;

use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Actions\Action;
use Filament\Pages\Concerns;
use Filament\Pages\Page as FilamentPage;
use Filament\Support\Exceptions\Halt;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;

/**
 * @property Form $form
 */
abstract class FormPage extends FilamentPage
{
    use Concerns\InteractsWithFormActions;

    public ?array $data = [];

    protected bool $shouldWithCancelFormAction = false;

    protected static string $view = 'filament.pages.form-page';

    public function mount(): void
    {
        $this->fillForm();
    }

    // You can override in target page
    public function getTitle(): string | Htmlable
    {
        return parent::getTitle();
    }

    // You can override in target page
    public function getHeading(): string | Htmlable
    {
        return parent::getHeading();
    }

    // You can override in target page
    public function getSubheading(): string | Htmlable | null
    {
        return parent::getSubheading();
    }

    // You can override in target page
    public static function getNavigationLabel(): string
    {
        return parent::getNavigationLabel();
    }

    // Do override in target page
    protected function getFormSchema(): array
    {
        return [];
    }

    // You can override in target page
    protected function getFormModel(): Model | string | null
    {
        return null;
    }

    // You can override in target page
    protected function getFormData(): array
    {
        return $this->getFormModel()?->attributesToArray() ?? [];
    }

    // You can override in target page
    public function form(Form $form): Form
    {
        return $form
            ->schema($this->getFormSchema())
            ->model($this->getFormModel())
            ->statePath('data');
    }

    // Do saving data
    abstract protected function handleSave(array $data): void;

    public function save(): void
    {
        try {
            $this->callHook('beforeValidate');

            $data = $this->form->getState();

            $this->callHook('afterValidate');

            $data = $this->mutateFormDataBeforeSave($data);

            $this->callHook('beforeSave');

            $this->handleSave($data);

            $this->callHook('afterSave');
        } catch (Halt $exception) {
            $this->sendFailedNotification();

            return;
        }

        if ($redirectUrl = $this->getRedirectUrl()) {
            $this->redirect($redirectUrl);
        }
    }

    protected function fillForm(): void
    {
        $data = $this->getFormData();

        $this->callHook('beforeFill');

        $data = $this->mutateFormDataBeforeFill($data);

        $this->form->fill($data);

        $this->callHook('afterFill');
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        return $data;
    }

    protected function getRedirectUrl(): ?string
    {
        return null;
    }

    protected function sendSuccessNotification(?string $message = null): ?Notification
    {
        if (blank($message) && blank($this->getSuccessNotificationMessage())) {
            return null;
        }

        return Notification::make()
            ->success()
            ->title($message ?? $this->getSuccessNotificationMessage())
            ->send();
    }

    protected function sendWarningNotification(?string $message = null): ?Notification
    {
        if (blank($message) && blank($this->getWarningNotificationMessage())) {
            return null;
        }

        return Notification::make()
            ->warning()
            ->title($message ?? $this->getWarningNotificationMessage())
            ->send();
    }

    protected function sendFailedNotification(?string $message = null): ?Notification
    {
        if (blank($message) && blank($this->getFailedNotificationMessage())) {
            return null;
        }

        return Notification::make()
            ->danger()
            ->title($message ?? $this->getFailedNotificationMessage())
            ->send();
    }

    protected function getSuccessNotificationMessage(): string
    {
        return __('app.notifications.save_success');
    }

    protected function getWarningNotificationMessage(): string
    {
        return __('app.notifications.something_went_wrong_try_again');
    }

    protected function getFailedNotificationMessage(): string
    {
        return __('app.notifications.save_failure');
    }

    public function getFormActions(): array
    {
        $formActions[] = $this->getSaveFormAction();

        if ($this->shouldWithCancelFormAction) {
            $formActions[] = $this->getCancelFormAction();
        }

        return $formActions;
    }

    protected function getSaveFormAction(): Action
    {
        return Action::make('save')
            ->label(__('app.commons.labels.save'))
            ->submit('save')
            ->keyBindings(['mod+s']);
    }

    protected function getCancelFormAction(): Action
    {
        return Action::make('cancel')
            ->label(__('app.commons.labels.cancel'))
            ->url(url()->previous())
            ->color('gray');
    }
}

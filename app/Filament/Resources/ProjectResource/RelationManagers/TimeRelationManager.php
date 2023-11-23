<?php

namespace App\Filament\Resources\ProjectResource\RelationManagers;

use App\Filament\Filters\SearchFilter;
use App\Utils\Html;
use App\Utils\TimeHelper;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use HusamTariq\FilamentTimePicker\Forms\Components\TimePickerField;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class TimeRelationManager extends RelationManager
{
    protected static string $relationship = 'times';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('app.resources.times.plural');
    }

    public function isReadOnly(): bool
    {
        return false;
    }

    public function form(Form $form): Form
    {
        $taskFormComponent = ! is_null($this->getOwnerRecord()->jira_link)
            ? Forms\Components\Grid::make()
                ->schema([
                    Forms\Components\TextInput::make('task')
                        ->label(__('app.fields.task'))
                        ->maxLength(250),

                    Forms\Components\TextInput::make('jira_issue')
                        ->label(__('app.fields.jira_issue'))
                        ->extraInputAttributes(['dir' => 'ltr'])
                        ->maxLength(250),
                ])
            : Forms\Components\TextInput::make('task')
                ->label(__('app.fields.task'))
                ->maxLength(250);

        return $form
            ->schema([
                $taskFormComponent,

                Forms\Components\DatePicker::make('date')
                    ->label(__('app.fields.date'))
                    ->default(now()->format('Y/m/d'))
                    ->rule('date:Y/m/d')
                    ->displayFormat('Y/m/d')
                    ->firstDayOfWeek(6)
                    ->closeOnDateSelection()
                    ->jalali()
                    ->required(),

                Forms\Components\Grid::make()
                    ->schema([
                        TimePickerField::make('start')
                            ->label(__('app.fields.start'))
                            ->okLabel(__('app.commons.labels.ok'))
                            ->cancelLabel(__('app.commons.labels.cancel'))
                            ->default(now()->startOfHour()->format('H:i'))
                            ->rule('date_format:H:i')
                            ->required()
                            ->lazy(),

                        TimePickerField::make('end')
                            ->label(__('app.fields.end'))
                            ->okLabel(__('app.commons.labels.ok'))
                            ->cancelLabel(__('app.commons.labels.cancel'))
                            ->rule('date_format:H:i')
                            ->after(fn(Forms\Get $get) => $get('start')),
                    ]),

                Forms\Components\RichEditor::make('description')
                    ->label(__('app.commons.fields.description')),

                Forms\Components\Toggle::make('is_calculate_on_sum')
                    ->label(__('app.fields.is_calculate_on_sum_toggle'))
                    ->default(true),
            ])
            ->columns(1);
    }

    public function table(Table $table): Table
    {
        return $table
            ->modelLabel(__('app.resources.times.singular'))
            ->pluralModelLabel(__('app.resources.times.plural'))
            ->recordAction(null)
            ->defaultSort('date', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('jira_issue')
                    ->label(__('app.fields.jira'))
                    ->icon('heroicon-o-link')
                    ->visible(fn() => ! is_null($this->getOwnerRecord()->jira_link))
                    ->url(fn($state) => ! is_null($state) ? $this->getOwnerRecord()->jira_link . '?selectedIssue=' . $state : null)
                    ->openUrlInNewTab()
                    ->sortable(),

                Tables\Columns\TextColumn::make('task')
                    ->label(__('app.fields.task'))
                    ->sortable(),

                Tables\Columns\TextColumn::make('date')
                    ->formatStateUsing(fn($state) => verta($state)->format('Y/m/d'))
                    ->label(__('app.fields.date'))
                    ->sortable(),

                Tables\Columns\TextColumn::make('start')
                    ->label(__('app.fields.start'))
                    ->sortable(),

                Tables\Columns\TextColumn::make('end')
                    ->label(__('app.fields.end'))
                    ->sortable(),

                Tables\Columns\TextColumn::make('total')
                    ->label(__('app.fields.time_work'))
                    ->formatStateUsing(fn($state) => TimeHelper::timestampToHoursMinutes($state))
                    ->summarize(
                        Tables\Columns\Summarizers\Sum::make()
                            ->label(__('app.fields.total_time'))
                            //->query(fn (\Illuminate\Database\Query\Builder $query) => $query->where('is_calculate_on_sum', true))
                            ->formatStateUsing(fn($state) => TimeHelper::timestampToHoursMinutes($state))
                    )
                    ->sortable(),
            ])
            ->filters([
                SearchFilter::make('jira_issue')
                    ->visible(fn() => ! is_null($this->getOwnerRecord()->jira_link)),

                SearchFilter::make('task'),

                Tables\Filters\TernaryFilter::make('is_calculate_on_sum')
                    ->label(__('app.fields.is_calculated_times_on_sum')),

                Tables\Filters\Filter::make('date')
                    ->form([
                        Forms\Components\DatePicker::make('date_from')
                            ->label(__('app.fields.date_from'))
                            ->rule('date:Y/m/d')
                            ->displayFormat('Y/m/d')
                            ->firstDayOfWeek(6)
                            ->closeOnDateSelection()
                            ->jalali(),

                        Forms\Components\DatePicker::make('date_until')
                            ->label(__('app.fields.date_until'))
                            ->rule('date:Y/m/d')
                            ->displayFormat('Y/m/d')
                            ->firstDayOfWeek(6)
                            ->closeOnDateSelection()
                            ->jalali(),
                    ])
                    ->columns(2)
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['date_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('date', '>=', $date),
                            )
                            ->when(
                                $data['date_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('date', '<=', $date),
                            );
                    }),

                Tables\Filters\Filter::make('today')
                    ->label(__('app.fields.today_entries'))
                    ->toggle()
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['isActive'],
                                fn (Builder $query, $date): Builder => $query->whereDate('date', today()->toDateString())
                            );
                    }),

                Tables\Filters\Filter::make('week')
                    ->label(__('app.fields.week_entries'))
                    ->toggle()
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['isActive'],
                                fn (Builder $query, $date): Builder =>
                                    $query->whereBetween('date', [
                                        verta()->startWeek()->toCarbon()->toDateString(),
                                        verta()->endWeek()->toCarbon()->toDateString()
                                    ])
                            );
                    }),

                Tables\Filters\Filter::make('month')
                    ->label(__('app.fields.month_entries'))
                    ->toggle()
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['isActive'],
                                fn (Builder $query, $date): Builder =>
                                $query->whereBetween('date', [
                                    verta()->startMonth()->toCarbon()->toDateString(),
                                    verta()->endMonth()->toCarbon()->toDateString()
                                ])
                            );
                    }),
            ])
            ->filtersFormColumns(4)
            ->filtersLayout(Tables\Enums\FiltersLayout::AboveContentCollapsible)
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\Action::make('description')
                    ->color('golden')
                    ->icon('heroicon-o-chat-bubble-bottom-center-text')
                    ->label(__('app.commons.fields.description'))
                    ->visible(fn($record) => ! is_null($record->description))
                    ->modalContent(fn($record) => Html::el('div')->setHtml($record->description))
                    ->modalSubmitAction(false)
                    ->modalCancelAction(false),

                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }
}

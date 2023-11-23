<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\TranslatableResource;
use App\Filament\Resources\ProjectResource\Pages;
use App\Filament\Resources\ProjectResource\RelationManagers;
use App\Models\Project;
use App\Utils\TimeHelper;
use Filament\Forms;
use Filament\Forms\Components\BaseFileUpload;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class ProjectResource extends Resource
{
    use TranslatableResource;

    protected static ?string $model = Project::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->label(__('app.commons.fields.title'))
                    ->maxLength(250)
                    ->required(),

                Forms\Components\TextInput::make('slug')
                    ->label(__('app.commons.fields.slug'))
                    ->hint(__('app.commons.hints.acceptable_chars'))
                    ->extraInputAttributes(['dir' => 'ltr'])
                    ->rule('regex:/^[a-zA-Z0-9-_]+$/')
                    ->unique(ignoreRecord: true)
                    ->minLength(3)
                    ->maxLength(100)
                    ->required(),

                Forms\Components\TextInput::make('link')
                    ->label(__('app.fields.project_link'))
                    ->extraInputAttributes(['dir' => 'ltr'])
                    ->maxLength(250)
                    ->url(),

                Forms\Components\TextInput::make('jira_link')
                    ->label(__('app.fields.project_jira_link'))
                    ->extraInputAttributes(['dir' => 'ltr'])
                    ->maxLength(250)
                    ->url(),

                Forms\Components\Textarea::make('description')
                    ->label(__('app.commons.fields.description'))
                    ->rows(5),

                Forms\Components\FileUpload::make('logo')
                    ->label(__('app.fields.logo'))
                    ->deletable()
                    ->deleteUploadedFileUsing(fn($record) => $record->removeImage())
                    ->disk(fn($record) => $record?->getImageDisk() ?? Project::MEDIA_DISK)
                    ->directory(fn($record) => $record?->getImageDirectory() ?? Project::LOGO_DIRECTORY)
                    ->getUploadedFileNameForStorageUsing(
                        fn(
                            BaseFileUpload $component,
                            TemporaryUploadedFile $file
                        ) => \Str::of($file->getClientOriginalName())->before('.')->toString() . '_' . time() . '.' . $file->getClientOriginalExtension()
                    )
                    ->rules(['nullable', 'mimes:jpg,jpeg,png,svg', 'max:1024']),
            ])->columns(1);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Split::make([
                    Infolists\Components\Section::make(__('app.infolists.project_info'))
                        ->schema([
                            Infolists\Components\ImageEntry::make('logo')
                                ->label('')
                                ->disk(fn($record) => $record->getImageDisk())
                                ->height('auto')
                                ->visible(fn($state) => ! is_null($state))
                                ->extraAttributes(['class' => 'justify-center']),

                            Infolists\Components\TextEntry::make('title')
                                ->label('')
                                ->size(Infolists\Components\TextEntry\TextEntrySize::Large)
                                ->alignCenter(),

                            Infolists\Components\TextEntry::make('description')
                                ->label('')
                                ->size(Infolists\Components\TextEntry\TextEntrySize::Large)
                                ->visible(fn($state) => ! is_null($state))
                                ->alignCenter(),

                            Infolists\Components\Actions::make([
                                Infolists\Components\Actions\Action::make('jira_link')
                                    ->color('golden')
                                    ->icon('heroicon-o-link')
                                    ->label(__('app.fields.jira_link'))
                                    ->visible(fn($record) => ! is_null($record->jira_link))
                                    ->url(fn($record) => $record->jira_link)
                                    ->openUrlInNewTab(),

                                Infolists\Components\Actions\Action::make('link')
                                    ->color('primary')
                                    ->icon('heroicon-o-link')
                                    ->label(__('app.fields.project_link'))
                                    ->visible(fn($record) => ! is_null($record->link))
                                    ->url(fn($record) => $record->link)
                                    ->openUrlInNewTab(),
                            ])
                            ->visible(fn($record) => ! is_null($record->link) && ! is_null($record->jira_link))
                            ->alignCenter(),
                        ])
                        ->columnSpanFull(),

                    Infolists\Components\Section::make(__('app.infolists.project_stats'))
                        ->schema([
                            Infolists\Components\TextEntry::make('created_at')
                                ->label(__('app.fields.today_time_work'))
                                ->formatStateUsing(
                                    fn($record) => TimeHelper::timestampToHoursMinutes(
                                        $record->times()->whereDate('date', today())->where('is_calculate_on_sum', true)->sum('total')
                                    )
                                )
                                ->size(Infolists\Components\TextEntry\TextEntrySize::Large)
                                ->alignEnd()
                                ->inlineLabel(),

                            Infolists\Components\TextEntry::make('created_at')
                                ->label(__('app.fields.week_time_work'))
                                ->formatStateUsing(
                                    fn($record) => TimeHelper::timestampToHoursMinutes(
                                        $record->times()
                                            ->whereBetween('date', [verta()->startWeek()->toCarbon(), verta()->endWeek()->toCarbon()])
                                            ->where('is_calculate_on_sum', true)
                                            ->sum('total')
                                    )
                                )
                                ->size(Infolists\Components\TextEntry\TextEntrySize::Large)
                                ->alignEnd()
                                ->inlineLabel(),

                            Infolists\Components\TextEntry::make('created_at')
                                ->label(__('app.fields.month_time_work'))
                                ->formatStateUsing(
                                    fn($record) => TimeHelper::timestampToHoursMinutes(
                                        $record->times()
                                            ->whereBetween('date', [verta()->startMonth()->toCarbon(), verta()->endMonth()->toCarbon()])
                                            ->where('is_calculate_on_sum', true)
                                            ->sum('total')
                                    )
                                )
                                ->size(Infolists\Components\TextEntry\TextEntrySize::Large)
                                ->alignEnd()
                                ->inlineLabel(),

                            Infolists\Components\TextEntry::make('created_at')
                                ->label(__('app.fields.total_time_work'))
                                ->formatStateUsing(fn($record) => TimeHelper::timestampToHoursMinutes($record->times()->where('is_calculate_on_sum', true)->sum('total')))
                                ->size(Infolists\Components\TextEntry\TextEntrySize::Large)
                                ->alignEnd()
                                ->inlineLabel(),
                        ])
                        ->columnSpanFull()
                        ->grow(false),
                ]),
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('logo')
                    ->label(__('app.fields.logo'))
                    ->disk(fn($record) => $record->getImageDisk()),

                Tables\Columns\TextColumn::make('title')
                    ->label(__('app.commons.fields.title'))
                    ->icon(fn($record) => ! is_null($record->link) ? 'heroicon-o-link' : null)
                    ->url(fn($record) => $record->link)
                    ->openUrlInNewTab()
                    ->searchable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('app.fields.total_time_work'))
                    ->formatStateUsing(
                        fn($record) => TimeHelper::timestampToHoursMinutes(
                            $record->times()->where('is_calculate_on_sum', true)->sum('total')
                        )
                    ),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('jira_link')
                    ->color('golden')
                    ->icon('heroicon-o-link')
                    ->label(__('app.fields.jira'))
                    ->url(fn($record) => $record->jira_link)
                    ->openUrlInNewTab(),

                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ])
            ->emptyStateActions([Tables\Actions\CreateAction::make()]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\TimeRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProjects::route('/'),
            //'create' => Pages\CreateProject::route('/create'),
            //'edit' => Pages\EditProject::route('/edit/{record}'),
            'view' => Pages\ViewProject::route('/view/{record}'),
        ];
    }
}

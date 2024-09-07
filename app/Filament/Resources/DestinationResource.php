<?php

namespace App\Filament\Resources;

use App\Enums\MonthsEnum;
use App\Enums\TimeZoneEnum;
use App\Filament\Resources\DestinationResource\Pages;
use App\Models\Destination;
use Filament\Tables\Actions\Action;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class DestinationResource extends Resource
{
    protected static ?string $model = Destination::class;

    protected static ?string $navigationIcon = 'heroicon-o-map-pin';

    protected static ?string $navigationGroup = 'Tour Services';
    protected static ?int $navigationSort = 3;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    protected static ?string $recordTitleAttribute = 'name';

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'timezone', 'country', 'currency'];
    }

    protected static int $globalSearchResultsLimit = 5;



    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('General Information')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->markAsRequired(false)
                            ->live(onBlur: true)
                            ->maxLength(255)
                            ->unique(Destination::class, 'name', ignoreRecord: true)
                            ->afterStateUpdated(function (string $operation, $state, Forms\Set $set) {
                                if ($operation !== 'create') {
                                    return;
                                }
                                // Replace spaces with hyphens and trim any leading/trailing spaces
                                $slug = preg_replace('/\s+/', '-', trim($state));
                                $set('slug', $slug);
                            }),
                        Forms\Components\TextInput::make('slug')
                            ->disabled()
                            ->dehydrated()
                            ->maxLength(255)
                            ->required()
                            ->helperText("will use for url you can't change it after create")
                            ->unique(Destination::class, 'slug', ignoreRecord: true),

                        Forms\Components\TextInput::make('language')
                            ->label('Language')
                            ->required()
                            ->maxLength(100),
                        Forms\Components\TextInput::make('country')
                            ->label('Country')
                            ->required()
                            ->maxLength(100),
                        Forms\Components\Select::make('timezone')
                            ->label('Timezone')
                            ->options(TimeZoneEnum::class)
                            ->helperText("country timezone")
                            ->required(),

                        Forms\Components\TextInput::make('currency')
                            ->label('Currency')
                            ->required()
                            ->maxLength(50),

                        // Handling the JSON field for peak season
                        Forms\Components\Select::make('peak_season')
                            ->label('Peak Season')
                            ->multiple()  // Allow multiple selections
                            ->options(MonthsEnum::class)
                            ->required(),
                        Forms\Components\FileUpload::make('images')
                            ->multiple()
                            ->disk('public')
                            ->directory('images/destinations')
                            ->label('Images')
                            ->required()
                            ->imageEditor()
                            ->panelLayout('grid')
                            ->enableReordering()
                            ->appendFiles()
                            ->columnSpan('full')
                            ->maxFiles(10)
                    ])
                    ->columns(2),


                Forms\Components\Section::make('Additional Information')
                    ->schema([
                        Forms\Components\TextInput::make('google_map_url')
                            ->label('Google Map URL')
                            ->required()
                            ->maxLength(65535)
                            ->url(),
                        Forms\Components\RichEditor::make('description')
                            ->label('Description')
                            ->maxLength(65535)
                            ->required(),
                    ])
                    ->columns(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('#')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\ImageColumn::make('images')
                    ->circular()
                    ->stacked()
                    ->limit(3)
                    ->limitedRemainingText(isSeparate: true),
                Tables\Columns\TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('timezone')
                    ->label('Timezone')
                    ->sortable(),
                Tables\Columns\TextColumn::make('currency')
                    ->label('Currency')
                    ->sortable(),
                Tables\Columns\TextColumn::make('peak_season')
                    ->label('Peak Season')
                    ->formatStateUsing(function ($state) {
                        return is_array($state) ? implode(', ', $state) : $state;
                    })
                    ->sortable(),

                Tables\Columns\TextColumn::make('currency')
                    ->label('Currency'),
                Tables\Columns\TextColumn::make('creator.name')  // Show creator's name
                    ->label('Created By')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime(),
            ])
            ->filters([
                Tables\filters\SelectFilter::make('timezone')
                    ->label('Timezone')
                    ->options(TimeZoneEnum::class),

                Tables\filters\SelectFilter::make('peak_season')
                    ->multiple()
                    ->options(MonthsEnum::class)
            ])->filtersTriggerAction(
                fn(Action $action) => $action
                    ->button()
                    ->label('Filter'),
            )
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ])
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDestinations::route('/'),
            'create' => Pages\CreateDestination::route('/create'),
            'edit' => Pages\EditDestination::route('/{record}/edit'),
        ];
    }
}

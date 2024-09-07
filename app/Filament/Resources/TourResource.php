<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TourResource\Pages;
use App\Models\Tour;
use App\Models\CategoryTour;
use App\Models\Destination;
use Filament\Tables\Actions\Action;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Section;
use Illuminate\Database\Eloquent\Builder;

class TourResource extends Resource
{
    protected static ?string $model = Tour::class;

    protected static ?string $navigationIcon = 'heroicon-o-map';

    protected static ?string $navigationGroup = 'Tour Services';
    protected static ?int $navigationSort = 2;
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    protected static ?string $recordTitleAttribute = 'name';

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'price', 'language'];
    }

    protected static int $globalSearchResultsLimit = 5;


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Tour Details')
                    ->tabs([
                        Tabs\Tab::make('Basic Information')
                            ->schema([
                                Section::make('General Info')
                                    ->schema([
                                        Forms\Components\TextInput::make('name')
                                            ->required()
                                            ->markAsRequired(false)
                                            ->live(onBlur: true)
                                            ->maxLength(255)
                                            ->unique(Tour::class, 'name', ignoreRecord: true)
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
                                            ->unique(Tour::class, 'slug', ignoreRecord: true),
                                        Forms\Components\TextInput::make('duration')
                                            ->label('Duration')
                                            ->maxLength(50)
                                            ->required(),

                                        Forms\Components\TextInput::make('price')
                                            ->label('Price')
                                            ->numeric()
                                            ->required()
                                            ->minValue(1)
                                            ->maxValue(100000)
                                            ->prefix('$'),

                                        Forms\Components\TextInput::make('group_size')
                                            ->label('Group Size')
                                            ->numeric()
                                            ->minValue(1)
                                            ->maxValue(20)
                                            ->required(),

                                        Forms\Components\TextInput::make('language')
                                            ->label('Language')
                                            ->required(),


                                        Forms\Components\FileUpload::make('images')
                                            ->multiple()
                                            ->disk('public')
                                            ->directory('images/tours')
                                            ->required()
                                            ->imageEditor()
                                            ->panelLayout('grid')
                                            ->enableReordering()
                                            ->appendFiles()
                                            ->columnSpan('full')
                                            ->maxFiles(10)

                                    ])
                                    ->columns(2),
                            ]),

                        Tabs\Tab::make('Details & Overview')
                            ->schema([
                                Section::make('Detailed Info')
                                    ->schema([
                                        Forms\Components\RichEditor::make('overview')
                                            ->label('Tour Overview')
                                            ->maxLength(65535)
                                            ->extraAttributes(['wire:ignore' => true]) // Ignore Livewire re-renders
                                            ->required(),

                                        Forms\Components\RichEditor::make('duration_details')
                                            ->label('Duration Details')
                                            ->maxLength(65535)
                                            ->extraAttributes(['wire:ignore' => true]) // Ignore Livewire re-renders
                                            ->required(),

                                    ]),
                            ]),

                        Tabs\Tab::make('Categories & Destinations')
                            ->schema([
                                Section::make('Associated')
                                    ->schema([
                                        Forms\Components\Select::make('destinations')
                                            ->label('Destinations')
                                            ->relationship('destinations', 'name')
                                            ->multiple()
                                            ->preload()
                                            ->required(),
                                        Forms\Components\Select::make('category_tour_id')
                                            ->label('Category')
                                            ->relationship('categoryTour', 'name')
                                            ->searchable()
                                            ->preload()
                                            ->required(),
                                    ]),
                            ]),
                    ])->columnSpan('full')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('images')
                    ->label('Images')
                    ->circular()
                    ->limit(3),

                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('categoryTour.name')
                    ->label('Category')
                    ->sortable(),

                Tables\Columns\TextColumn::make('creator.name')  // Show creator's name
                    ->label('Created By')
                    ->sortable(),

                Tables\Columns\TextColumn::make('price')
                    ->label('Price')
                    ->sortable()
                    ->money('usd', true),

                Tables\Columns\TextColumn::make('group_size')
                    ->label('Group Size')
                    ->sortable(),

                Tables\Columns\TextColumn::make('language')
                    ->label('Language')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('destinations')
                    ->relationship('destinations', 'name')
                    ->multiple()
                    ->preload()
                    ->searchable()
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTours::route('/'),
            'create' => Pages\CreateTour::route('/create'),
            'edit' => Pages\EditTour::route('/{record}/edit'),
        ];
    }
}

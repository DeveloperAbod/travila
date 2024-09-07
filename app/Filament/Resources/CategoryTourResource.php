<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryTourResource\Pages;
use App\Models\CategoryTour;
use Filament\Tables\Actions\Action;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;

class CategoryTourResource extends Resource
{
    protected static ?string $model = CategoryTour::class;

    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';

    protected static ?string $navigationGroup = 'Tour Services';
    protected static ?int $navigationSort = 1;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    protected static ?string $recordTitleAttribute = 'name';

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'slug'];
    }

    protected static int $globalSearchResultsLimit = 5;



    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Tours Category Details')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->markAsRequired(false)
                            ->live(onBlur: true)
                            ->maxLength(255)
                            ->unique(CategoryTour::class, 'name', ignoreRecord: true)
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
                            ->unique(CategoryTour::class, 'slug', ignoreRecord: true),

                        Forms\Components\FileUpload::make('image')
                            ->label('Category Image')
                            ->disk('public')
                            ->directory('images/ToursCategories')
                            ->imageEditor()
                            ->image()
                            ->required()
                            ->columnspan('full'),

                        Forms\Components\RichEditor::make('description')
                            ->label('Description')
                            ->extraAttributes(['wire:ignore' => true]) // Ignore Livewire re-renders
                            ->maxLength(65535)
                            ->required()
                            ->columnspan('full'),

                        Forms\Components\Toggle::make('status')
                            ->label('Active Status')
                            ->default(1)
                            ->required(),
                    ])->columns(2)
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
                Tables\Columns\ImageColumn::make('image')
                    ->label('Image')
                    ->toggleable()
                    ->circular(),

                Tables\Columns\TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('slug')
                    ->label('Slug')
                    ->searchable()
                    ->toggleable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('creator.name')  // Show creator's name
                    ->label('Created By')
                    ->sortable(),
                Tables\Columns\ToggleColumn::make('status'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime(),
            ])
            ->filters([
                TernaryFilter::make('status')
                    ->label('status')
                    ->boolean()
                    ->trueLabel('Only activate Categories')
                    ->falseLabel('Only deactivate Categories')
                    ->native(true),
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
            'index' => Pages\ListCategoryTours::route('/'),
            'create' => Pages\CreateCategoryTour::route('/create'),
            'edit' => Pages\EditCategoryTour::route('/{record}/edit'),
        ];
    }


    public static function getModelLabel(): string
    {
        return 'Tours Category';
    }

    public static function getPluralModel(): string
    {
        return 'Tours Categories';
    }
    public static function getPluralModelLabel(): string
    {
        return 'Tours Categories';
    }
}

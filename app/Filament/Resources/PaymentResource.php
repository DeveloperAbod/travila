<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentResource\Pages;
use App\Models\Payment;
use Filament\Tables\Actions\Action;
use Filament\Forms;

use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Form;
use Filament\Tables\Filters\TernaryFilter;

class PaymentResource extends Resource
{
    protected static ?string $model = Payment::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';

    protected static ?string $navigationGroup = 'Management';
    protected static ?int $navigationSort = 2;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    protected static ?string $recordTitleAttribute = 'name';

    public static function getGloballySearchableAttributes(): array
    {
        return ['name'];
    }

    protected static int $globalSearchResultsLimit = 5;


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Payment Details')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(50)
                            ->unique(Payment::class, 'name', ignoreRecord: true)
                            ->label('Name'),
                        Forms\Components\FileUpload::make('image')
                            ->disk('public')
                            ->directory('images/payments')
                            ->imageEditor()
                            ->required()
                            ->label('Image'),
                        Forms\Components\Toggle::make('status')
                            ->label('Active')
                            ->helperText('Toggle to activate or deactivate the payment method')
                            ->default(true),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Name')
                    ->searchable(),
                Tables\Columns\ImageColumn::make('image')
                    ->disk('public')
                    ->label('Image'),
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPayments::route('/'),
            'create' => Pages\CreatePayment::route('/create'),
            'edit' => Pages\EditPayment::route('/{record}/edit'),
        ];
    }
}

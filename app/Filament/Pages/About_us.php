<?php

namespace App\Filament\Pages;

use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Exceptions\Halt;
use Illuminate\Contracts\Support\Htmlable;
use App\Models\AboutUs;


class About_us extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';


    protected static ?string $navigationGroup = 'Management';
    protected static ?int $navigationSort = 1;


    protected static string $view = 'filament.pages.about-us';

    public $data = [];
    protected $record;


    public function mount(): void
    {
        // Load the record with id = 1
        $this->record = AboutUs::find(1);

        if ($this->record) {
            $this->form->fill($this->record->toArray());
        } else {
            // If record is not found, provide a log message and prevent further actions.
            Notification::make()
                ->danger()
                ->title('error')
                ->body("record doesn't exists")
                ->send();
            return;
        }
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('About Us Details')->schema([
                    TextInput::make('email')
                        ->email()
                        ->required()
                        ->maxLength(255),
                    TextInput::make('google_map_url')
                        ->label('Google Map URL')
                        ->required()
                        ->maxLength(65535)
                        ->url(),
                    TextInput::make('call_number')
                        ->required()
                        ->rule('regex:/^7\d{8}$/')
                        ->helperText('call number must start with 7 and have 9 digits'),

                    TextInput::make('whats_number')
                        ->required()
                        ->rule('regex:/^7\d{8}$/')
                        ->helperText('whatsapp number must start with 7 and have 9 digits'),
                ])->columns(2)
            ])
            ->statePath('data');
    }


    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label(__('filament-panels::resources/pages/edit-record.form.actions.save.label'))
                ->submit('save'),
        ];
    }

    public function save(): void
    {
        $record = AboutUs::find(1);

        if (!$record) {
            Notification::make()
                ->danger()
                ->title('Error!')
                ->body('error while save')
                ->send();
            return;
        }

        try {
            $data = $this->form->getState();
            $record->update($data);

            Notification::make()
                ->success()
                ->title('About use updated successfully')
                ->send();
        } catch (Halt $exception) {
            return;
        }
    }


    public function getTitle(): string | Htmlable
    {
        return 'About Us';
    }

    public static function getNavigationLabel(): string
    {
        return 'About Us';
    }
}

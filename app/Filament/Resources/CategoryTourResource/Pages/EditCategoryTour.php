<?php

namespace App\Filament\Resources\CategoryTourResource\Pages;

use App\Filament\Resources\CategoryTourResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCategoryTour extends EditRecord
{
    protected static string $resource = CategoryTourResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

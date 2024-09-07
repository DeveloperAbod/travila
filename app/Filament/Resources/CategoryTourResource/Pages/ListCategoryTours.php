<?php

namespace App\Filament\Resources\CategoryTourResource\Pages;

use App\Filament\Resources\CategoryTourResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCategoryTours extends ListRecords
{
    protected static string $resource = CategoryTourResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

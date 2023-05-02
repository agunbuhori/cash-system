<?php

namespace App\Filament\Resources\CashResource\Pages;

use App\Filament\Resources\CashResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCash extends EditRecord
{
    protected static string $resource = CashResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

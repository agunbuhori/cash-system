<?php

namespace App\Filament\Resources\SalesResource\Widgets;

use App\Models\Sales;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;
use Filament\Widgets\Widget;

class TotalSales extends BaseWidget
{
    // protected static string $view = 'filament.resources.sales-resource.widgets.total-sales';

    protected function getCards(): array
    {
        $revenue = Sales::selectRaw("SUM( (amount * price) - (tax / 100) * (amount * price) ) AS total")->first()->total;

        return [
            Card::make('ITEM TERJUAL', Sales::sum('amount')),
            Card::make('TOTAL PENDAPATAN', "Rp. " . number_format($revenue)),
        ];
    }
}

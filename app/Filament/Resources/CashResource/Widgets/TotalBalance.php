<?php

namespace App\Filament\Resources\CashResource\Widgets;

use App\Models\Cash;
use Filament\Widgets\StatsOverviewWidget\Card;
use Filament\Widgets\Widget;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class TotalBalance extends BaseWidget
{
    // protected static string $view = 'filament.resources.cash-resource.widgets.total-balance';

    protected function getCards(): array
    {
        $total = Cash::sum('nominal');
        $total_in = Cash::where("type", "IN")->sum('nominal');
        $total_out = Cash::where("type", "OUT")->sum('nominal');

        return [
            Card::make('TOTAL PEMASUKAN', 'Rp. ' . number_format($total_in)),
            Card::make('TOTAL PENGELUARAN', 'Rp. ' . number_format(abs($total_out))),
            Card::make('SALDO KAS', 'Rp. ' . number_format($total)),
        ];
    }
}

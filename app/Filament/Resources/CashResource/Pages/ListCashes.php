<?php

namespace App\Filament\Resources\CashResource\Pages;

use App\Filament\Resources\CashResource;
use App\Filament\Resources\CashResource\Widgets\TotalBalance;
use Filament\Forms\Components\Select;
use Filament\Pages\Actions;
use Filament\Pages\Actions\Action;
use Filament\Resources\Pages\ListRecords;

class ListCashes extends ListRecords
{
    protected static string $resource = CashResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Buat Jurnal'),
            Action::make('Cetak Laporan Jurnal')
                ->action(function (array $data): void {
                    redirect()->to("/cash/report?y={$data['year']}&m={$data['month']}");
                })
                ->color('secondary')
                ->icon('heroicon-o-book-open')
                ->form([
                    Select::make('month')
                        ->default((int) date('m'))
                        ->options([
                            1 => 'Januari',
                            2 => 'Februari',
                            3 => 'Maret',
                            4 => 'April',
                            5 => 'Mei',
                            6 => 'Juni',
                            7 => 'Juli',
                            8 => 'Agustus',
                            9 => 'September',
                            10 => 'Oktober',
                            11 => 'November',
                            12 => 'Desember'
                        ])->label('Bulan'),
                    Select::make('year')
                        ->default(date('Y'))
                        ->placeholder('Semua')
                        ->options(function () {
                            $result = [];
                            foreach (range(2023, date('Y')) as $year) {
                                $result[$year] = $year;
                            }
                            return $result;
                        })->label('Tahun')
                ])
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            TotalBalance::class
        ];
    }
}

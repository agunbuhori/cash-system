<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SalesResource\Pages;
use App\Filament\Resources\SalesResource\RelationManagers;
use App\Filament\Resources\SalesResource\Widgets\TotalSales;
use App\Models\Product;
use App\Models\Sales;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SalesResource extends Resource
{
    protected static ?string $model = Sales::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    protected static ?string $navigationLabel = 'Penjualan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('product_id')
                    ->searchable()
                    ->required()
                    ->getSearchResultsUsing(function (string $search) {
                        $filters = Product::select('id', 'code', 'name', 'price', 'amount')
                            ->where('name', 'like', "%{$search}%")
                            ->orWhere('code', 'like', "%{$search}%")
                            ->where('amount', '>', 0)
                            ->limit(50)
                            ->get();

                        $result = [];
                        foreach ($filters as $f) {
                            $number = "Rp." . number_format($f->price);
                            $result[$f->id] = "{$f->code} - {$f->name} - {$number}";
                        }
                        return $result;
                    })
                    ->getOptionLabelUsing(fn ($value): ?string => Product::find($value)?->name)
                    ->label('Produk'),
                TextInput::make('amount')->numeric()->label('Jumlah')->required()->default(1),
                Select::make('tax')->options([
                    '0' => 'Tidak ada',
                    '10' => 'PPN 10%'
                ])->default('0')->label('Pajak')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('product.picture')->label('GAMBAR'),
                TextColumn::make('product.name')->label('PRODUK')->searchable(),
                TextColumn::make('created_at')->label('TANGGAL')->dateTime('d/m/Y H:m'),
                TextColumn::make('amount')->label('JUMLAH'),
                TextColumn::make('price')->label('HARGA/ITEM')->money('idr', true)->alignRight(),
                TextColumn::make('tax')->label('PAJAK')->getStateUsing(function (Model $record) {
                    return $record->tax . '%';
                }),
                TextColumn::make('total')->label('TOTAL')
                    ->getStateUsing(function (Model $record): float {
                        $total =  $record->amount * $record->price;
                        $total -= ($record->tax / 100) * $total;
                        return $total;
                    })->money('idr', true)->alignRight(),
            ])
            ->filters([
                Filter::make('month')->form([
                    Select::make('month')
                        ->default((int) date('m'))
                        ->placeholder('Semua')
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
                ])->query(function ($query, $data) {
                    $query->when($data['month'], function ($query) use ($data) {
                        $query->whereMonth('created_at', $data['month']);
                    })->when($data['year'], function ($query) use ($data) {
                        $query->whereYear('created_at', $data['year']);
                    });
                }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),

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
            'index' => Pages\ListSales::route('/'),
            // 'create' => Pages\CreateSales::route('/create'),
            // 'edit' => Pages\EditSales::route('/{record}/edit'),
        ];
    }

    public static function getWidgets(): array
    {
        return [
            TotalSales::class
        ];
    }
}

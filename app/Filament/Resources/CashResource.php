<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CashResource\Pages;
use App\Filament\Resources\CashResource\RelationManagers;
use App\Filament\Resources\CashResource\Widgets\TotalBalance;
use App\Models\Cash;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;

class CashResource extends Resource
{
    protected static ?string $model = Cash::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';
    protected static ?string $navigationLabel = 'Jurnal';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('number')->disabled()->placeholder('Terisi otomatis')
                    ->hiddenOn('create')
                    ->label('Nomor'),
                Select::make('type')->options([
                    'IN' => 'Pemasukkan',
                    'OUT' => 'Pengeluaran'
                ])
                    ->default('IN')
                    ->hiddenOn('edit')
                    ->required()
                    ->label('Jenis'),
                Textarea::make('description')->required()->label('Deskripsi'),
                TextInput::make('nominal')->numeric()->required(),
                DatePicker::make('date')->required()->label('Tanggal')->default(today())->disabledOn('edit'),
                Grid::make(1)->schema([
                    KeyValue::make('detail')->valueLabel('Harga')->keyLabel('Item')

                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('number')->searchable()->label('NO.')->sortable(),
                TextColumn::make('description')->searchable()->label('DESKRIPSI')->words(10),
                BadgeColumn::make('type')->label('JENIS')
                    ->enum([
                        'IN' => 'PEMASUKAN',
                        'OUT' => 'PENGELUARAN',
                    ])
                    ->colors([
                        'success' => 'IN',
                        'danger' => 'OUT',
                    ]),
                TextColumn::make('nominal')->money('IDR', true)->alignRight()->label('NOMINAL')->sortable(),
                TextColumn::make('date')->date('d/m/Y')->label('TANGGAL')
            ])
            ->filters([
                SelectFilter::make('type')->options([
                    'IN' => 'Pemasukkan',
                    'OUT' => 'Pengeluaran'
                ])->label('Jenis'),
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
                        $query->whereMonth('date', $data['month']);
                    })->when($data['year'], function ($query) use ($data) {
                        $query->whereYear('date', $data['year']);
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

    public static function getWidgets(): array
    {
        return [
            TotalBalance::class
        ];
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
            'index' => Pages\ListCashes::route('/'),
            'create' => Pages\CreateCash::route('/create'),
            // 'edit' => Pages\EditCash::route('/{record}/edit'),
        ];
    }
}

<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $navigationGroup = 'Inventori';
    protected static ?string $navigationLabel = 'Produk';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('code')->unique('products', 'code')->disabledOn('edit')->label('Kode Barang')
                    ->placeholder('Terisi otomatis')
                    ->hiddenOn('edit')
                    ->disabled(),
                Select::make('product_category_id')
                    ->relationship('product_category', 'name')
                    ->required()
                    ->label('Kategori Produk'),
                TextInput::make('name')->required()->label('Nama Barang'),
                TextInput::make('purchase_price')->required()->numeric()->label('Harga Modal'),
                TextInput::make('price')->required()->numeric()->label('Harga Jual'),
                TextInput::make('amount')->required()->numeric()->label('Stok'),
                FileUpload::make('picture')->label('Gambar'),
                Toggle::make('update_cash')->default(false)->label('Catat pengeluaran produksi di jurnal')->hiddenOn('edit'),
                Grid::make(1)->schema([
                    RichEditor::make('description')

                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('picture')->label('GAMBAR'),
                TextColumn::make('code')->label('KODE')->searchable(),
                TextColumn::make('name')->label('NAMA PRODUK')->searchable(),
                TextColumn::make('product_category.name')->label('KATEGORI'),
                TextColumn::make('amount')->label('STOK'),
                TextColumn::make('purchase_price')->money('IDR', true)->label('HARGA MODAL')->alignRight(),
                TextColumn::make('price')->money('IDR', true)->label('HARGA JUAL')->alignRight(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageProducts::route('/'),
        ];
    }
}

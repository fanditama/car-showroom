<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PromotionResource\Pages;
use App\Filament\Resources\PromotionResource\RelationManagers;
use App\Models\Promotion;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\RawJs;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PromotionResource extends Resource
{
    protected static ?string $model = Promotion::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('car_id')
                    ->relationship('car', 'brand')
                    ->label('Merek Mobil')
                    ->placeholder('Pilih merek mobil')
                    ->required()
                    ->validationMessages([
                        'required' => 'Form merek mobil tidak boleh kosong.'
                    ]),
                TextInput::make('title')
                    ->live(debounce: 500)
                    ->label('Judul Promosi')
                    ->placeholder('Masukkan judul promosi')
                    ->required()
                    ->string()
                    ->maxLength(255)
                    ->validationMessages([
                        'string' => 'Form judul harus berupa teks.',
                        'maxLength' => 'Form judul tidak boleh lebih dari :max karakter.',
                    ]),
                Textarea::make('description')
                    ->live(debounce: 500)
                    ->label('Deskripsi Promosi')
                    ->placeholder('Masukkan deskripsi promosi')
                    ->string()
                    ->maxLength(255)
                    ->validationMessages([
                        'string' => 'Form deskripsi harus berupa teks.',
                        'maxLength' => 'Form deskripsi tidak boleh lebih dari :max karakter.',
                    ]),
                TextInput::make('discount')
                    ->live(debounce: 500)
                    ->label('Diskon Promosi')
                    ->placeholder('Masukan diskon promosi')
                    ->numeric()
                    ->prefix('Rp ')
                    ->stripCharacters(',')
                    ->mask(RawJs::make('$money($input)'))
                    ->validationMessages([
                        'numeric' => 'Form diskon harus berupa angka.',
                    ]),
                DatePicker::make('start_date')
                    ->label('Tanggal Mulai Promosi')
                    ->placeholder('Pilih tanggal mulai promosi')
                    ->format('d-m-Y')
                    ->displayFormat('d-m-Y')
                    ->validationMessages([
                        'format' => 'Form tanggal harus berbentuk format tanggal.'
                    ]),
                DatePicker::make('end_date')
                    ->label('Tanggal Berakhir Promosi')
                    ->placeholder('Pilih tanggal berakhir promosi')
                    ->format('d-m-Y')
                    ->displayFormat('d-m-Y')
                    ->validationMessages([
                        'format' => 'Form tanggal harus berbentuk format tanggal.'
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('car.brand')
                    ->label('Merek Mobil')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('title')
                    ->label('Judul Promosi')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('description')
                    ->label('Deskripsi Promosi')
                    ->searchable()
                    ->sortable(),
                    Tables\Columns\TextColumn::make('discount')
                    ->label('Diskon Promosi')
                    ->money('IDR')
                    ->formatStateUsing(function ($state) {
                        // Ubah koma menjadi titik
                        return str_replace(',', '.', number_format($state, 0, ',', '.'));
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('start_date')
                    ->label('Tanggal Mulai Promosi')
                    ->date('d-m-Y')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_date')
                    ->label('Tanggal Berakhir Promosi')
                    ->date('d-m-Y')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListPromotions::route('/'),
            'create' => Pages\CreatePromotion::route('/create'),
            'edit' => Pages\EditPromotion::route('/{record}/edit'),
        ];
    }
}

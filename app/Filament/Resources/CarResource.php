<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CarResource\Pages;
use App\Filament\Resources\CarResource\RelationManagers;
use App\Models\Car;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
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

class CarResource extends Resource
{
    protected static ?string $model = Car::class;
    protected static ?string $navigationIcon = 'heroicon-o-truck';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('brand')
                    ->live(debounce: 500)
                    ->label('Merek Mobil')
                    ->placeholder('Masukkan merek mobil')
                    ->required()
                    ->string()
                    ->maxLength(255)
                    ->validationMessages([
                        'required' => 'Form merek tidak boleh kosong.',
                        'string' => 'Form merek harus berupa teks.',
                        'maxLength' => 'Form merek tidak boleh lebih dari :max karakter.',
                    ]),
                TextInput::make('model')
                    ->live(debounce: 500)
                    ->label('Model Mobil')
                    ->placeholder('Masukkan model mobil')
                    ->required()
                    ->string()
                    ->maxLength(255)
                    ->validationMessages([
                        'required' => 'Form model tidak boleh kosong.',
                        'string' => 'Form model harus berupa teks.',
                        'maxLength' => 'Form model tidak boleh lebih dari :max karakter.',
                    ]),
                TextInput::make('year')
                    ->live(debounce: 500)
                    ->label('Tahun Produksi')
                    ->placeholder('Masukkan tahun produksi mobil')
                    ->required()
                    ->integer()
                    ->minValue(1900)
                    ->validationMessages([
                        'required' => 'Form tahun tidak boleh kosong.',
                        'integer' => 'Form tahun harus berupa angka.',
                        'minValue' => 'Form tahun tidak boleh kurang dari :min.',
                    ]),

                TextInput::make('price')
                    ->live(debounce: 500)
                    ->label('Harga Mobil')
                    ->placeholder('Masukkan harga mobil')
                    ->required()
                    ->numeric()
                    ->prefix('Rp ')
                    ->stripCharacters(',')
                    ->mask(RawJs::make('$money($input)'))
                    ->validationMessages([
                        'required' => 'Form harga tidak boleh kosong.',
                        'numeric' => 'Form harga harus berupa angka.',
                    ]),
                Select::make('type')
                    ->label('Tipe Mobil')
                    ->placeholder('Pilih tipe mobil')
                    ->options([
                        'sedan' => 'Sedan',
                        'suv' => 'SUV',
                        'MPV' => 'MPV',
                        'hatchback' => 'Hatchback',
                        'sport' => 'Sport',
                    ])
                    ->rules(['required', 'in:sedan,suv,MPV,hatchback,sport'])
                    ->validationMessages([
                        'required' => 'Form tipe tidak boleh kosong.',
                        'in' => 'Form tipe harus berupa salah satu dari opsi yang tersedia.',
                    ]),
                Textarea::make('description')
                    ->live(debounce: 500)
                    ->label('Deskripsi Mobil')
                    ->nullable()
                    ->string()
                    ->placeholder('Masukkan deskripsi mobil')
                    ->validationMessages([
                        'string' => 'Form deskripsi harus berupa teks.',
                    ]),
                FileUpload::make('image_url')
                    ->label('Gambar Mobil')
                    ->placeholder('Klik disini untuk memilih gambar mobil')
                    ->nullable()
                    ->directory('cars')
                    ->image()
                    ->imageEditor()
                    ->imageEditorMode(2)
                    ->preserveFilenames()
                    ->maxSize(5000)
                    ->validationMessages([
                        'image' => 'Form gambar harus berupa file gambar.',
                        'max' => 'Form gambar tidak boleh lebih dari :max kilobytes.',
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('brand')
                    ->label('Merek Mobil')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('model')
                    ->label('Model Mobil')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('year')
                    ->label('Tahun Produksi')
                    ->sortable(),
                Tables\Columns\TextColumn::make('price')
                    ->label('Harga Mobil')
                    ->money('IDR')
                    ->formatStateUsing(function ($state) {
                        // Ubah koma menjadi titik
                        return str_replace(',', '.', number_format($state, 0, ',', '.'));
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('type')
                    ->label('Tipe Mobil')
                    ->sortable(),
                Tables\Columns\TextColumn::make('description')
                    ->label('Deskripsi Mobil')
                    ->limit(50)
                    ->searchable(),
                Tables\Columns\ImageColumn::make('image_url')
                    ->label('Gambar Mobil')
                    ->circular()
                    ->size(60)
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
            'index' => Pages\ListCars::route('/'),
            'create' => Pages\CreateCar::route('/create'),
            'edit' => Pages\EditCar::route('/{record}/edit'),
        ];
    }
}

<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CreditApplicationResource\Pages;
use App\Filament\Resources\CreditApplicationResource\RelationManagers;
use App\Models\CreditApplication;
use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\RawJs;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CreditApplicationResource extends Resource
{
    protected static ?string $model = CreditApplication::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->label('Nama Pengguna')
                    ->placeholder('Pilih nama pengguna')
                    ->required()
                    ->validationMessages([
                        'required' => 'Form merek mobil tidak boleh kosong.'
                    ]),
                Select::make('car_id')
                    ->relationship('car', 'brand')
                    ->label('Merek Mobil')
                    ->placeholder('Pilih merek mobil')
                    ->required()
                    ->validationMessages([
                        'required' => 'Form merek mobil tidak boleh kosong.'
                    ]),
                DateTimePicker::make('application_date')
                    ->live(debounce: 500)
                    ->label('waktu Pengajuan')
                    ->placeholder('Masukan waktu pengajuan')
                    ->format('d/m/Y H:i:s')
                    ->seconds(false)
                    ->timezone('Asia/Jakarta')
                    ->displayFormat('d/m/Y H:i:s')
                    ->seconds(true)
                    ->validationMessages([
                        'format' => 'Form tanggal harus berbentuk format tanggal dan waktu.'
                    ]),
                TextInput::make('income')
                    ->live(debounce: 500)
                    ->label('Jumlah Penghasilan')
                    ->placeholder('Masukkan jumlah Penghasilan')
                    ->required()
                    ->numeric()
                    ->prefix('Rp ')
                    ->stripCharacters(',')
                    ->mask(RawJs::make('$money($input)'))
                    ->validationMessages([
                        'required' => 'Form harga tidak boleh kosong.',
                        'numeric' => 'Form harga harus berupa angka.',
                    ]),
                Select::make('status')
                    ->live(debounce: 500)
                    ->label('Status Kredit')
                    ->placeholder('Pilih status kredit')
                    ->options([
                        'tertunda' => 'Tertunda',
                        'disetujui' => 'Disetujui',
                        'ditolak' => 'Ditolak',
                    ])
                    ->in(['tertunda', 'disetujui', 'ditolak'])
                    ->validationMessages([
                        'in' => 'Form tipe harus berupa salah satu dari opsi yang tersedia.',
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Nama Pengguna')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('car.brand')
                    ->label('Merek Mobil')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('application_date')
                    ->label('Waktu Pengajuan')
                    ->dateTime('d-m-Y | H:i:s')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('income')
                    ->label('Jumlah Penghasilan')
                    ->money('IDR')
                    ->formatStateUsing(function ($state) {
                        // Ubah koma menjadi titik
                        return str_replace(',', '.', number_format($state, 0, ',', '.'));
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status Kredit')
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
            'index' => Pages\ListCreditApplications::route('/'),
            'create' => Pages\CreateCreditApplication::route('/create'),
            'edit' => Pages\EditCreditApplication::route('/{record}/edit'),
        ];
    }
}

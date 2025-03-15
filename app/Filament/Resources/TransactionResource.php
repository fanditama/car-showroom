<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransactionResource\Pages;
use App\Filament\Resources\TransactionResource\RelationManagers;
use App\Models\Transaction;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\RawJs;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Carbon\Carbon;
use Filament\Forms\Components\DateTimePicker;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

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
                DateTimePicker::make('transaction_date')
                    ->live(debounce: 500)
                    ->label('waktu Transaksi')
                    ->placeholder('Masukan waktu transaksi')
                    ->format('d-m-Y H:i:s')
                    ->seconds(false)
                    ->timezone('Asia/Jakarta')
                    ->displayFormat('d-m-Y H:i:s')
                    ->seconds(true)
                    ->validationMessages([
                        'format' => 'Form tanggal harus berbentuk format tanggal dan waktu.'
                    ]),
                TextInput::make('total_amount')
                    ->live(debounce: 500)
                    ->label('Jumlah Pembayaran')
                    ->placeholder('Masukkan jumlah pembayaran')
                    ->required()
                    ->numeric()
                    ->prefix('Rp ')
                    ->stripCharacters(',')
                    ->mask(RawJs::make('$money($input)'))
                    ->validationMessages([
                        'required' => 'Form harga tidak boleh kosong.',
                        'numeric' => 'Form harga harus berupa angka.',
                    ]),
                Select::make('payment_method')
                    ->live(debounce: 500)
                    ->label('Metode Pembayaran')
                    ->placeholder('Pilih metode pembayaran')
                    ->options([
                        'transfer_bank' => 'Transfer Bank',
                        'credit_card' => 'Kartu Kredit',
                        'cash' => 'Uang Tunai',
                    ])
                    ->in(['transfer_bank', 'credit_card', 'cash'])
                    ->validationMessages([
                        'in' => 'Form tipe harus berupa salah satu dari opsi yang tersedia.',
                    ]),
                Select::make('status')
                    ->live(debounce: 500)
                    ->label('Status Transaksi')
                    ->placeholder('Pilih status transaksi')
                    ->options([
                        'pending' => 'Tertunda',
                        'success' => 'Sukses',
                        'cancel' => 'Dibatalkan',
                    ])
                    ->in(['pending', 'success', 'cancel'])
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
                Tables\Columns\TextColumn::make('transaction_date')
                    ->label('Waktu Transaksi')
                    ->dateTime('d-m-Y | H:i:s')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_amount')
                    ->label('Jumlah Pembayaran')
                    ->money('IDR')
                    ->formatStateUsing(function ($state) {
                        // Ubah koma menjadi titik
                        return str_replace(',', '.', number_format($state, 0, ',', '.'));
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('payment_method')
                    ->label('Metode Pembayaran')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status Transaksi')
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
            'index' => Pages\ListTransactions::route('/'),
            'create' => Pages\CreateTransaction::route('/create'),
            'edit' => Pages\EditTransaction::route('/{record}/edit'),
        ];
    }
}

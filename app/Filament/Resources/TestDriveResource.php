<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TestDriveResource\Pages;
use App\Filament\Resources\TestDriveResource\RelationManagers;
use App\Models\TestDrive;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TestDriveResource extends Resource
{
    protected static ?string $model = TestDrive::class;

    protected static ?string $pluralModelLabel = 'Tes Mobil';

    protected static ?string $navigationIcon = 'heroicon-o-beaker';

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
                DatePicker::make('testdrive_date')
                    ->label('Tanggal Test Mengemudi')
                    ->placeholder('Pilih tanggal test mengemudi')
                    ->required()
                    ->format('d-m-Y')
                    ->displayFormat('d-m-Y')
                    ->validationMessages([
                        'required' => 'Form tanggal test mengemudi tidak boleh kosong.',
                        'format' => 'Form tanggal harus berbentuk format tanggal.'
                    ]),
                Select::make('status')
                    ->options([
                        'tertunda' => 'Tertunda',
                        'disetujui' => 'Disetujui',
                        'ditolak' => 'Ditolak',
                    ])
                    ->label('Status')
                    ->placeholder('Pilih status')
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
                Tables\Columns\TextColumn::make('testdrive_date')
                    ->label('Tanggal Test Mengemudi')
                    ->date('d-m-Y')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'tertunda' => 'Tertunda',
                        'disetujui' => 'Disetujui',
                        'ditolak' => 'Ditolak',
                    ]),
            Tables\Filters\Filter::make('testdrive_date')
                ->form([
                    DatePicker::make('from_date')
                        ->label('Dari Tanggal'),
                    DatePicker::make('to_date')
                        ->label('Sampai Tanggal'),
                ])
                // cek apakah from_date dan to_date ada, jika ada, tambahkan query
                ->query(function (Builder $query, array $data): Builder {
                    return $query
                        ->when(
                            $data['from_date'],
                            fn (Builder $query, $date): Builder => $query->whereDate('testdrive_date', '>=', $date),
                        )
                        ->when(
                            $data['to_date'],
                            fn (Builder $query, $date): Builder => $query->whereDate('testdrive_date', '<=', $date),
                        );
                }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListTestDrives::route('/'),
            'create' => Pages\CreateTestDrive::route('/create'),
            'edit' => Pages\EditTestDrive::route('/{record}/edit'),
        ];
    }
}

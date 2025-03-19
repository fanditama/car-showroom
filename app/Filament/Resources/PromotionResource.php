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

    protected static ?string $pluralModelLabel = 'Promosi';

    protected static ?string $navigationIcon = 'heroicon-o-receipt-percent';

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
                Tables\Filters\Filter::make('discount')
                    ->form([
                        Forms\Components\TextInput::make('min_discount')
                            ->label('Penghasilan Terendah')
                            ->placeholder('Masukan angka tanpa titik (.)')
                            ->prefix('Rp ')
                            ->numeric(),
                        Forms\Components\TextInput::make('max_discount')
                            ->label('Penghasilan Tertinggi')
                            ->placeholder('Masukan angka tanpa titik (.)')
                            ->prefix('Rp ')
                            ->numeric(),
                    ])
                    // cek apakah min_discount dan max_discount ada, jika ada, tambahkan query
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['min_discount'],
                                fn (Builder $query, $minDiscount): Builder => $query->where('discount', '>=', $minDiscount),
                            )
                            ->when(
                                $data['max_discount'],
                                fn (Builder $query, $maxDiscount): Builder => $query->where('discount', '<=', $maxDiscount),
                            );
                    })
                    // tampilkan indikator diskon terendah dan diskon tertinggi
                    ->indicateUsing(function (array $data): ?string {
                            $indicators = [];

                            if (!empty($data['min_discount'])) {
                                $indicators[] = 'Diskon Terendah: Rp ' . number_format($data['min_discount'], 0, ',', '.');
                            }

                            if (!empty($data['max_discount'])) {
                                $indicators[] = 'Diskon Tertinggi: Rp ' . number_format($data['max_discount'], 0, ',', '.');
                            }

                            return !empty($indicators) ? implode(' - ', $indicators) : null;
                    }),
                    Tables\Filters\Filter::make('date_range')
                        ->form([
                            Forms\Components\DatePicker::make('start_date')
                                ->label('Tanggal Mulai')
                                ->placeholder('Pilih tanggal mulai'),
                            Forms\Components\DatePicker::make('end_date')
                                ->label('Tanggal Akhir')
                                ->placeholder('Pilih tanggal akhir'),
                        ])
                        // cek apakah start_date dan end_date ada, jika ada, tambahkan query
                        ->query(function (Builder $query, array $data): Builder {
                            return $query
                                ->when(
                                    $data['start_date'],
                                    fn (Builder $query, $startDate): Builder => $query->whereDate('start_date', '>=', $startDate),
                                )
                                ->when(
                                    $data['end_date'],
                                    fn (Builder $query, $endDate): Builder => $query->whereDate('end_date', '<=', $endDate),
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
            'index' => Pages\ListPromotions::route('/'),
            'create' => Pages\CreatePromotion::route('/create'),
            'edit' => Pages\EditPromotion::route('/{record}/edit'),
        ];
    }
}

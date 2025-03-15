<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\RawJs;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->live(debounce: 500)
                    ->label('Nama pengguna')
                    ->placeholder('Masukkan nama pengguna')
                    ->required()
                    ->string()
                    ->maxLength(255)
                    ->validationMessages([
                        'required' => 'Form nama tidak boleh kosong.',
                        'string' => 'Form nama harus berupa teks.',
                        'maxLength' => 'Form nama tidak boleh lebih dari :max karakter.',
                    ]),
                TextInput::make('email')
                    ->live(debounce: 500)
                    ->label('Alamat Email')
                    ->placeholder('Masukkan alamat email')
                    ->required()
                    ->email()
                    ->maxLength(255)
                    ->validationMessages([
                        'required' => 'Form email tidak boleh kosong.',
                        'email' => 'Form email harus berupa email.',
                        'maxLength' => 'Form email tidak boleh lebih dari :max karakter.',
                    ]),
                TextInput::make('password')
                    ->live(debounce: 500)
                    ->label('Password')
                    ->placeholder('Masukkan password')
                    ->required()
                    ->password()
                    ->validationMessages([
                        'required' => 'Form password tidak boleh kosong.',
                    ]),
                Textarea::make('address')
                    ->live(debounce: 500)
                    ->label('Alamat')
                    ->nullable()
                    ->string()
                    ->placeholder('Masukkan alamat')
                    ->validationMessages([
                        'string' => 'Form alamat harus berupa teks.',
                    ]),
                TextInput::make('phone_number')
                    ->live(debounce: 500)
                    ->label('Nomor Telepon')
                    ->placeholder('Masukkan nomor telepon, cth:08123456789')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Pengguna')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('Alamat Email')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('address')
                    ->label('Alamat')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('phone_number')
                    ->label('Nomor Telepon')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                //
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}

<?php

namespace App\Filament\Pages\Auth;

use Filament\Forms\Components\Component;
use Filament\Forms\Components\TextInput;
use Filament\Actions\Action;

use Filament\Pages\Auth\Register as BaseRegister;

class Register extends BaseRegister
{
    // atur judul halaman
    public function getTitle(): string
    {
        return 'Daftar';
    }

    // atur judul form
    public function getHeading(): string
    {
        return 'Daftar';
    }

    protected function getNameFormComponent(): Component
    {
        return TextInput::make('name')
            ->label('Nama Panjang')
            ->required()
            ->maxLength(255)
            ->autofocus();
    }

    protected function getEmailFormComponent(): Component
    {
        return TextInput::make('email')
            ->label('Alamat Email')
            ->email()
            ->required()
            ->unique('users');
    }

    protected function getPasswordFormComponent(): Component
    {
        return TextInput::make('password')
            ->label('Kata Sandi')
            ->required()
            ->password()
            ->confirmed()
            ->minLength(8);
    }

    protected function getPasswordConfirmationFormComponent(): Component
    {
        return TextInput::make('password_confirmation')
            ->label('Konfirmasi Kata Sandi')
            ->required()
            ->password();
    }

    public function loginAction(): Action
    {
        return Action::make('login')
            ->link()
            ->label('Kembali ke halaman Masuk')
            ->url(filament()->getLoginUrl());
    }

    public function getRegisterFormAction(): Action
    {
        return Action::make('register')
            ->label('Daftar')
            ->submit('register');
    }

    // atur URL halaman register
    public static function getUrl(array $parameters = []): string
    {
        return route('filament.admin.auth.register', $parameters);
    }

}

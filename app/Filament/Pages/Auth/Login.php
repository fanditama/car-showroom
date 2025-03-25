<?php
namespace App\Filament\Pages\Auth;

use Filament\Actions\Action;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Auth\Login as BaseLogin;
use Illuminate\Auth\AuthenticationException;

class Login extends BaseLogin
{
    // atur judul halaman
    public function getTitle(): string
    {
        return 'Masuk';
    }

    // atur judul form
    public function getHeading(): string
    {
        return 'Masuk';
    }

    protected function getEmailFormComponent(): Component
    {
        return TextInput::make('email')
            ->label('Alamat Email')
            ->email()
            ->required()
            ->autofocus();
    }

    protected function getPasswordFormComponent(): Component
    {
        return TextInput::make('password')
            ->label('Kata Sandi')
            ->password()
            ->required();
    }

    protected function getRememberFormComponent(): Component
    {
        return Checkbox::make('remember')
            ->label('Ingat saya');
    }

    public function registerAction(): Action
    {
        return Action::make('register')
            ->link()
            ->label('Daftar Akun')
            ->url(filament()->getRegistrationUrl());
    }

    protected function getAuthenticateFormAction(): Action
    {
        return Action::make('authenticate')
            ->label('Masuk')
            ->submit('authenticate');
    }

    // atur URL halaman login
    public static function getUrl(array $parameters = []): string
    {
        return route('filament.admin.auth.login', $parameters);
    }

    // Tambah konfirmasi jika email gagal pada saat proses autentikasi
    protected function onAuthenticationFailed(AuthenticationException $exception): void
    {
        $this->addError('email', __('filament-panels::pages/auth/login.messages.failed'));
    }
}

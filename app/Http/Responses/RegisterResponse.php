<?php

namespace App\Http\Responses;

use App\Filament\Pages\Auth\Login;
use Illuminate\Http\RedirectResponse;
use Livewire\Features\SupportRedirects\Redirector;

class RegisterResponse extends \Filament\Http\Responses\Auth\RegistrationResponse
{
    public function toResponse($request): RedirectResponse|Redirector
    {
        // logout pengguna setelah registrasi
        auth()->logout();
        // setelah register kembali ke halaman login
        return redirect()->to(Login::getUrl());
    }
}

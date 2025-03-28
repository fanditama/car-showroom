<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class AuthController extends Controller
{
    /**
     * Show the login form
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle login attempt
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ],
        [
            'email.required' => 'Form alamat email harus diisi',
            'email.email' => 'Form alamat email harus dalam bentuk email yang valid',
            'password.required' => 'Form password harus diisi',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            // Flash success message
            session()->flash('toast_success', 'Selamat, anda berhasil masuk!');

            return redirect()->intended('/');
        }

        return redirect('/login')->withErrors([
            'email' => 'Kredensial yang diberikan tidak sesuai dengan catatan kami.',
        ])->onlyInput('email');
    }

    /**
     * Handle user logout
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Flash success message
        session()->flash('toast_success', 'Anda telah keluar dari akun.');

        return redirect('/');
    }

    /**
     * Show the registration form
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Handle registration
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ],
        [
            'name.required' => 'Form nama harus diisi',
            'name.max' => 'Form nama tidak boleh melebihi 255 karakter',
            'email.required' => 'Form alamat email harus diisi',
            'email.email' => 'Form alamat email harus dalam bentuk email yang valid',
            'email.max' => 'Form alamat email tidak boleh melebihi 255 karakter',
            'password.required' => 'Form password harus diisi',
            'password.confirmed' => 'Form harus melakukan konfirmasi password',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);

        // Flash success message
        session()->flash('toast_success', 'Selamat, akun anda berhasil dibuat!');

        return redirect('/');
    }
}

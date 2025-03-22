<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use ValidatesRequests;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    
    public function redirect(string $provider)
    {
        $allowedProviders = ['google', 'facebook'];

        if (!in_array($provider, $allowedProviders)) {
            return redirect()->back()->withErrors(['provider' => 'Oauth provider salah!']);
        }
 
        return Socialite::driver($provider)->redirect();
    }

    public function callback(string $provider)
    {
        $this->validateProvider($provider);

        $response = Socialite::driver($provider)->user();

        $user = User::firstWhere(['email' => $response->getEmail()]);

        if ($user) {
            $user->update([$provider . '_id' => $response->getId()]);
        } else {
            $user = User::create([
                $provider . '_id' => $response->getId(),
                'name'            => $response->getName(),
                'email'           => $response->getEmail(),
                'password'        => '',
            ]);
        }

        auth()->login($user);

        return redirect()->intended('/');
    }

    //  Validate the OAuth provider.
    protected function validateProvider(string $provider)
    {
        $validator = Validator::make(
            ['provider' => $provider],
            ['provider' => 'in:google,facebook'],
            ['provider.in' => 'Provider yang dipilih salah.']
        );

        $validator->validate();
    }
}

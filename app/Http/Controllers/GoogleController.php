<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirectToGoogle(): RedirectResponse
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback(): RedirectResponse
    {
        $googleUser = Socialite::driver('google')->user();

        $user = $this->findOrCreateUser('google_id', $googleUser->id, $googleUser->name, $googleUser->email);

        Auth::login($user, true);

        return redirect('/todo');
    }

    protected function findOrCreateUser($provider, $providerId, $name, $email)
    {
        $existingUser = User::where($provider, $providerId)->first();

        if ($existingUser) {
            return $existingUser;
        }

        $newUser = User::create([
            'name' => $name,
            'email' => $email,
            $provider => $providerId,
            'auth_type' => $provider,
            'password' => bcrypt(Str::random()),
        ]);

        return $newUser;
    }
}

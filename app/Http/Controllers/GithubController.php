<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GitHubController extends Controller
{
    public function redirectToGitHub(): RedirectResponse
    {
        return Socialite::driver('github')->redirect();
    }

    public function handleGitHubCallback(): RedirectResponse
    {
        $githubUser = Socialite::driver('github')->user();

        $user = $this->findOrCreateUser('github_id', $githubUser->id, $githubUser->name, $githubUser->email);

        Auth::login($user, true);

        return redirect('/home');
    }

    protected function findOrCreateUser($provider, $providerId, $name, $email)
    {
        $existingUser = User::where($provider, $providerId)->first();

        if ($existingUser) {
            return $existingUser;
        }

        $existingUserByEmail = User::where('email', $email)->first();

        if ($existingUserByEmail) {
            // Update the existing user with GitHub ID
            $existingUserByEmail->$provider = $providerId;
            $existingUserByEmail->save();

            return $existingUserByEmail;
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

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use App\Models\User;
use App\Notifications\PasswordResetNotification;

class ProfileController extends Controller
{
    public function index()
    {
        return view('todo.profile', ['user' => Auth::user()]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . Auth::id(),
        ]);

        $user = Auth::user();
        if ($user instanceof User) {
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $user->save();
        } else {
            return back()->withErrors(['error' => 'User not authenticated or not found.']);
        }

        return back()->with('success', 'Profile updated.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        $user = Auth::user();
        if ($user instanceof User) {
            if (!Hash::check($request->input('current_password'), $user->password)) {
                return back()->withErrors(['current_password' => 'Current password is incorrect']);
            }

            $user->password = Hash::make($request->input('new_password'));
            $user->save();

            return back()->with('success', 'Password updated.');
        } else {
            return back()->withErrors(['error' => 'User not authenticated or not found.']);
        }
    }

    public function unlinkProvider($provider)
    {
        $user = Auth::user();
        if ($user instanceof User) {
            if ($provider == 'google') {
                $user->google_id = null;
            } elseif ($provider == 'github') {
                $user->github_id = null;
            }
            $user->save();
            
            return back()->with('success', ucfirst($provider) . ' account unlinked.');
        } else {
            return back()->withErrors(['error' => 'User not authenticated or not found.']);
        }
    }

    public function sendPasswordResetCode(Request $request)
    {
        $user = Auth::user();
        if ($user instanceof User) {
            $user->password_reset_code = Str::random(6);
            $user->save();

            Notification::send($user, new PasswordResetNotification($user->password_reset_code));

            return back()->with('success', 'Password reset code sent to your email.');
        } else {
            return back()->withErrors(['error' => 'User not authenticated or not found.']);
        }
    }

    public function verifyPasswordResetCode(Request $request)
    {
        $request->validate([
            'reset_code' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        $user = Auth::user();
        if ($user instanceof User) {
            if ($user->password_reset_code != $request->input('reset_code')) {
                return back()->withErrors(['reset_code' => 'Invalid reset code']);
            }

            $user->password = Hash::make($request->input('new_password'));
            $user->password_reset_code = null;
            $user->save();

            return back()->with('success', 'Password reset successfully.');
        } else {
            return back()->withErrors(['error' => 'User not authenticated or not found.']);
        }
    }
}
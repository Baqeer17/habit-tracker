<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class GoogleAuthController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            $user = User::where('email', $googleUser->getEmail())->first();

            if (!$user) {
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'password' => Hash::make(Str::random(16)), // Random password
                    'google_id' => $googleUser->getId(),
                ]);
            } else {
                // Update google_id if it's missing (e.g. user registered with email manually first)
                if (empty($user->google_id)) {
                    $user->update(['google_id' => $googleUser->getId()]);
                }
            }

            Auth::login($user, true);
            request()->session()->regenerate();

            return redirect('/dashboard');

        } catch (\Exception $e) {
            return redirect('/login')->with('error', 'Google Login failed: ' . $e->getMessage());
        }
    }
}

<?php

namespace App\Http\Controllers;


use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class GoogleController extends Controller
{
    // 1. Lempar user ke Google
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    // 2. Terima user dari Google
    public function handleGoogleCallback(Request $request)
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            $user = User::where('email', $googleUser->getEmail())->first();

            if (!$user) {
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'password' => Hash::make('password_rahasia_' . rand(1000, 9999)),
                ]);
            } else {
                if (empty($user->google_id)) {
                    $user->update(['google_id' => $googleUser->getId()]);
                }
            }

            Auth::login($user);
            $request->session()->regenerate();

            return redirect()->intended('dashboard');

        } catch (\Exception $e) {
            return redirect('/login')->with('error', 'Gagal login dengan Google');
        }
    }
}
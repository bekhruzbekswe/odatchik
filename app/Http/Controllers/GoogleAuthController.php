<?php

namespace App\Http\Controllers;

use App\Models\User;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    /**
     * Redirect to Google OAuth
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle Google OAuth callback
     */
    public function handleGoogleCallback()
    {
        $googleUser = Socialite::driver('google')->user();

        // Find or create user by oauth credentials
        $user = User::firstOrCreate(
            [
                'oauth_provider' => 'google',
                'oauth_id' => $googleUser->getId(),
            ],
            [
                'name' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                'email_verified_at' => now(),
            ]
        );

        // Create access token
        $token = $user->createToken('Google OAuth Token')->accessToken;

        // Redirect to a page that will store the token and redirect to dashboard
        return view('GoogleAuth.callback', [
            'token' => $token,
            'user' => $user,
        ]);
    }
}

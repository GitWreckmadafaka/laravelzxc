<?php

namespace App\Http\Controllers;

use Laravel\Socialite\Facades\Socialite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class GoogleAuthController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
           
            Log::info('Google callback hit.');

            $user = Socialite::driver('google')->user();

            Log::info('Google user information retrieved:', [
                'email' => $user->getEmail(),
                'name' => $user->getName(),
                'google_id' => $user->getId(),
            ]);

          
            $authUser = User::where('email', $user->getEmail())->first();

            if (!$authUser) {
                
                $authUser = User::create([
                    'name' => $user->getName(),
                    'email' => $user->getEmail(),
                    'google_id' => $user->getId(),
                    'password' => bcrypt(Str::random(40)), 
                    'is_active' => true, 
                    'birthdate' => '2000-01-01',
                    'gender' => 'other', 
                    'age' => 0,
                ]);
                Log::info('New user created:', ['email' => $authUser->email]);
            }

           
            if (!$authUser->hasVerifiedEmail()) {
                $authUser->markEmailAsVerified();
                Log::info('User email marked as verified:', ['email' => $authUser->email]);
            }

          
            if (!$authUser->is_active) {
                Log::warning('Inactive user attempted to log in:', ['email' => $authUser->email]);
                return redirect('/login')->withErrors(['google' => 'Your account is inactive.']);
            }

          
            $authUser->update(['last_login' => now()]);

            
            Auth::login($authUser, true);
            Log::info('User logged in with last login updated:', ['email' => $authUser->email, 'last_login' => $authUser->last_login]);

            return redirect()->route('home');
        } catch (\Exception $e) {
          
            Log::error('Error during Google login:', ['message' => $e->getMessage()]);
            return redirect('/login')->withErrors(['google' => 'Unable to login using Google.']);
        }
    }

}

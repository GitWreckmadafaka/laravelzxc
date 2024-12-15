<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
class GitHubController extends Controller
{
    public function redirectToGitHub()
    {
        return Socialite::driver('github')->redirect();
    }

    public function handleGitHubCallback()
    {
        try {
            // Get the GitHub user information
            $githubUser = Socialite::driver('github')->user();

            // Log the GitHub user details to check if they are correct
            Log::info('GitHub User:', [
                'name' => $githubUser->getName() ?: 'GitHub User',
                'email' => $githubUser->getEmail(),
                'github_id' => $githubUser->getId(),
                'avatar' => $githubUser->getAvatar(),
            ]);

            // Find or create a user in your local database
            $user = User::firstOrCreate(
                ['email' => $githubUser->getEmail()],
                [
                    'name' => $githubUser->getName(),
                    'github_id' => $githubUser->getId(),
                    'age' => 1, 
                    'birthdate' => null, 
                    'password' => Hash::make('defaultpassword'),
                ]
            );

            // Log the user in
            Auth::login($user, true);

            // Redirect to the home page or the page where you want to go
            return redirect('/home');

        } catch (\Exception $e) {
            // Handle any errors during the authentication process
            Log::error('GitHub login error: ' . $e->getMessage());
            return redirect('/login')->withErrors(['msg' => 'Failed to login using GitHub.']);
        }
    }
}

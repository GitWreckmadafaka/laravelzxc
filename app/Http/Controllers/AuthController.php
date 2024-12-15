<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Password;
use App\Notifications\CustomResetPasswordNotification;
class AuthController extends Controller
{
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        // Validate the incoming data
        $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'middlename' => 'nullable|string|max:255',
            'age' => 'required|integer|min:1',
            'birthdate' => 'required|date',
            'gender' => 'required|in:male,female,other',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
            'g-recaptcha-response' => 'required',
            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',  // Validate profile image
            'address' => 'required|string|max:255',  // Validate address
            'zipcode' => 'required|string|max:20',   // Validate zip code
        ]);

        // Combine first, middle, and last name into one full name field
        $fullName = $request->firstname . ' ' . ($request->middlename ? $request->middlename . ' ' : '') . $request->lastname;

        // Create a new user instance
        $user = new User();
        $user->name = $fullName;  // Store the full name
        $user->firstname = $request->firstname; // Store first name
        $user->lastname = $request->lastname;   // Store last name
        $user->middlename = $request->middlename; // Store middle name (nullable)
        $user->age = $request->age;
        $user->birthdate = $request->birthdate;
        $user->gender = $request->gender;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->email_verified_at = null; // Assuming you're handling email verification elsewhere

        // Handle profile image upload
        if ($request->hasFile('profile_image')) {
            // Store the profile image
            $path = $request->file('profile_image')->store('profile_images', 'public');
            $user->profile_image = $path;
        }

        // Store the address and zipcode
        $user->address = $request->address;
        $user->zipcode = $request->zipcode;

        $user->save();

        // Send verification email (if needed)
        $this->sendVerificationEmail($user);

        // Redirect with success message
        return redirect()->route('login.form')->with('message', 'Registration successful! Please check your email to verify your account.');
    }



    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user) {
          ("User is_active status: " . $user->is_active);

            if ($user->is_active) { // Check if the user is active
                if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                    // Check if the user is an admin
                    if ($user->is_admin) {
                        $user->update(['last_login' => now()]);
                        return redirect()->route('admin.users.index');
                    }

                    // For regular users
                    if ($user->email_verified_at) {
                        $otp = rand(100000, 999999);  // Generate a 6-digit numeric OTP
                        session(['otp' => $otp, 'otp_verified' => false]);

                        Mail::raw("Your OTP is: $otp", function ($message) use ($user) {
                            $message->to($user->email);
                            $message->subject('Your OTP Code');
                        });


                        $user->update(['last_login' => now()]);
                        return redirect()->route('otp.form');
                    } else {
                        Auth::logout();
                        return redirect()->route('login.form')->with('error', 'Please verify your email address first.');
                    }
                }
            } else {
                return redirect()->route('login.form')->with('error', 'Your account is inactive.');
            }
        }

        return redirect()->route('login.form')->with('error', 'Invalid credentials.');
    }



    public function showOtpForm()
    {
        return view('otp');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate(['otp' => 'required|string']);

        if ($request->otp == session('otp')) {
            session(['otp_verified' => true]);
            return redirect('/home');
        }

        return redirect()->route('otp.form')->with('error', 'Invalid OTP. Please try again.');
    }

    protected function sendVerificationEmail($user)
    {
        $verificationUrl = route('verification.verify', ['id' => $user->id, 'hash' => sha1($user->email)]);

        Mail::send('emails.verify', ['user' => $user, 'url' => $verificationUrl], function ($message) use ($user) {
            $message->to($user->email);
            $message->subject('Verify Your Email Address');
        });
    }

    public function verifyEmail(Request $request, $id)
    {
        $user = User::findOrFail($id);

        if (sha1($user->email) !== $request->hash) {
            return redirect('/login')->with('error', 'Invalid verification link.');
        }

        $user->email_verified_at = now();
        $user->save();

        return redirect('/login')->with('message', 'Email verified successfully! You can now log in.');
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/login')->with('message', 'You have been logged out successfully.');
    }

    public function showEditProfileForm()
    {
        return view('profile.edit_profile');
    }

    public function requestProfileUpdate(Request $request)
    {
        $request->validate([
            'firstname' => 'required|string|max:255',
            'middlename' => 'nullable|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . Auth::id(),
            'age' => 'required|integer|min:1',
            'birthdate' => 'required|date',
            'gender' => 'required|in:male,female,other',
            'zipcode' => 'required|string|max:10', // validate the zipcode
            'address' => 'required|string|max:255', // validate the address
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // validate the profile image
        ]);

        // Handle the profile image upload if present
        $profileImagePath = null;
        if ($request->hasFile('profile_image')) {
            $profileImagePath = $request->file('profile_image')->store('profile_images', 'public');
        }

                // Generate OTP and store in session
        // Generate OTP and store in session
        $otp = rand(100000, 999999);  // Generate a 6-digit numeric OTP
        session([
            'otp' => $otp,
            'otp_verified' => false,
            'profile_data' => array_merge($request->except(['_token', 'profile_image']), ['profile_image' => $profileImagePath]),
        ]);

        // Send OTP to the user's email
        Mail::raw("Your OTP for profile update is: $otp", function ($message) {
            $message->to(Auth::user()->email);
            $message->subject('Your OTP Code for Profile Update');
        });


        // Redirect to OTP verification form
        return redirect()->route('otp.profile.update.form');
    }

    public function showOtpProfileUpdateForm()
    {
        return view('auth.otp_profile_update');  // OTP input page
    }

    public function verifyOtpForProfileUpdate(Request $request)
    {
        $request->validate(['otp' => 'required|string']);

        if ($request->otp == session('otp')) {
            $this->updateProfileWithSessionData();
            session()->forget(['otp', 'otp_verified', 'profile_data']);
            return redirect('/home')->with('message', 'Profile updated successfully!');
        }

        return redirect()->route('otp.profile.update.form')->with('error', 'Invalid OTP. Please try again.');
    }

    protected function updateProfileWithSessionData()
    {
        $user = Auth::user();
        $data = session('profile_data');

        // Combine first name, middle name, and last name
        $fullName = $data['firstname'] . ' ' . ($data['middlename'] ?? '') . ' ' . $data['lastname'];

        // Update user profile with session data
        $user->name = $fullName;
        $user->email = $data['email'];
        $user->age = $data['age'];
        $user->birthdate = $data['birthdate'];
        $user->gender = $data['gender'];
        $user->zipcode = $data['zipcode'];
        $user->address = $data['address']; // Address added here

        // Update profile image if available
        if (isset($data['profile_image'])) {
            $user->profile_image = $data['profile_image'];
        }
        /** @var \App\Models\User $user **/
        $user->save();

        // Send email notification about the update
        Mail::send('emails.profile_updated', ['user' => $user], function ($message) use ($user) {
            $message->to($user->email);
            $message->subject('Your Profile has been Updated');
        });
    }


    public function home()
    {
        return view('home');
    }

    public function showResetRequestForm()
    {
        return view('auth.passwords.email');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);


        $user = User::where('email', $request->email)->first();

        if ($user) {

            $token = Password::getRepository()->create($user);


            $user->notify(new CustomResetPasswordNotification($token));

            return back()->with('status', __('A password reset link has been sent to your email.'));
        }
        return back()->withErrors(['email' => __('The provided email does not match our records.')]);
    }

    public function showResetForm(Request $request, $token = null)
    {
        return view('auth.passwords.reset')->with(['token' => $token, 'email' => $request->email]);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed',
            'token' => 'required',
        ]);

        $response = Password::reset($request->only('email', 'password', 'password_confirmation', 'token'), function ($user, $password) {
            $user->password = Hash::make($password);
            $user->save();
        });

        return $response === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', __('Your password has been reset! You can now log in.'))
            : back()->withErrors(['email' => __('Failed to reset password. Please try again.')]);
    }

    public function showAdminLoginForm()
    {
        return view('admin.login');
    }

    public function adminLogin(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($request->username === 'admin' && $request->password === 'admin') {
            Auth::loginUsingId(1);
            return redirect()->route('admin.users.index');
        }

        return back()->withErrors(['login' => 'Invalid credentials.']);
    }


}

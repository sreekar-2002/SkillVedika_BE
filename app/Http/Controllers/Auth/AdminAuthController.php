<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class AdminAuthController extends Controller
{
    // public function login(Request $request)
    // {
    //     Log::debug('Login request', [
    //         'request' => $request->all(),
    //     ]);
    //     // 1. Validate request
    //     $credentials = $request->validate([
    //         'email'    => ['required', 'email'],
    //         'password' => ['required', 'string'],
    //     ]);

    //     // 2. Rate limiting
    //     $throttleKey = Str::lower($credentials['email']) . '|' . $request->ip();

    //     if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
    //         throw ValidationException::withMessages([
    //             'email' => ['Too many login attempts. Please try again later.'],
    //         ]);
    //     }

    //     // 3. Fetch admin
    //     $admin = \App\Models\Admin::where('email', $credentials['email'])->first();

    //     if (!$admin || !Hash::check($credentials['password'], $admin->password)) {
    //         RateLimiter::hit($throttleKey, 60);

    //         throw ValidationException::withMessages([
    //             'email' => ['Invalid email or password.'],
    //         ]);
    //     }

    //     RateLimiter::clear($throttleKey);

    //     // 4. Create Sanctum token for API authentication
    //     // $token = $admin->createToken('api-token', ['*'])->plainTextToken;

    //     // 5. Log the admin in using Laravel Auth (SESSION) for web routes
    //     // Note: login() internally calls session->migrate(true) which regenerates the session ID
    //     // This prevents session fixation attacks
    //     Auth::guard('web')->login($admin);

    //     // 6. Ensure session is saved (Laravel usually does this automatically, but being explicit)
    //     // The session should now contain the authenticated user
    //     $request->session()->save();

    //     // 8. Debug: Log session info in development
    //     if (config('app.env') !== 'production') {
    //         $sessionId = $request->session()->getId();
    //         $authenticatedUser = $request->user();

    //         Log::debug('Login session created', [
    //             'session_id' => $sessionId,
    //             'session_name' => config('session.cookie'),
    //             'user_id' => $admin->id,
    //             'authenticated_user_id' => $authenticatedUser ? $authenticatedUser->id : 'NULL',
    //             'session_driver' => config('session.driver'),
    //             'session_data_keys' => array_keys($request->session()->all()),
    //         ]);

    //         // Verify the user is actually authenticated in the session
    //         if (!$authenticatedUser) {
    //             Log::warning('⚠️  User login succeeded but session authentication failed!', [
    //                 'admin_id' => $admin->id,
    //                 'session_id' => $sessionId,
    //             ]);
    //         }
    //     }

    //     // 9. Return user data - session cookie is automatically set by Laravel
    //     // The session cookie will be sent with the response headers
    //     return response()->json([
    //         'message' => 'Login successful',
    //         'user' => [
    //             'id' => $admin->id,
    //             'name' => $admin->name,
    //             'email' => $admin->email,
    //             'avatar' => $admin->avatar ?? null,
    //         ]
    //     ], 200)->withHeaders([
    //         // Ensure CORS headers are set for cookie transmission
    //         'Access-Control-Allow-Credentials' => 'true',
    //     ]);
    // }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }

        $request->session()->regenerate();

        return response()->json([
            'message' => 'Login successful',
            'user' => Auth::user() ? [
                'id' => Auth::user()->id,
                'name' => Auth::user()->name,
                'email' => Auth::user()->email,
                'avatar' => Auth::user()->avatar ?? null,
            ] : null
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(['message' => 'Logged out'], 200);
    }
}

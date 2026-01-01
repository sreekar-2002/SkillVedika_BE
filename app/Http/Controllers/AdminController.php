<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    /**
     * Verify current authentication status - THE SOURCE OF TRUTH
     * This endpoint is called by middleware on EVERY route access
     * Returns 200 ONLY if admin is currently authenticated and session is valid
     * Returns 401 if not authenticated, session expired, or token revoked
     */
    public function me(Request $request)
    {
        // dd($request);
        // Debug logging in development
        // if (config('app.env') !== 'production') {
        //     $allCookies = $request->header('Cookie', '');
        //     $cookieNames = [];
        //     if ($allCookies) {
        //         $cookieNames = array_map(function($cookie) {
        //             return explode('=', trim($cookie))[0];
        //         }, explode(';', $allCookies));
        //     }

        //     Log::debug('AdminController::me called', [
        //         'has_auth_header' => $request->hasHeader('Authorization'),
        //         'has_cookie_header' => !empty($allCookies),
        //         'cookie_names' => $cookieNames,
        //         'has_laravel_session' => $request->hasCookie(config('session.cookie')),
        //         'has_auth_token_cookie' => $request->hasCookie('auth_token'),
        //         'session_id' => $request->session()->getId() ?? 'no session',
        //         'user_from_request' => $request->user() ? 'authenticated' : 'not authenticated',
        //     ]);
        // }

        $admin = $request->user();

        // NO ADMIN = NOT LOGGED IN (401)
        if (!$admin) {
            if (config('app.env') !== 'production') {
                $sessionId = $request->session()->getId();
                $sessionData = $request->session()->all();

                Log::debug('AdminController::me - User not authenticated', [
                    'session_exists' => $sessionId !== null,
                    'session_id' => $sessionId,
                    'session_data_keys' => array_keys($sessionData),
                    'has_login_web_key' => isset($sessionData['login_web_' . config('auth.guards.web.provider')]),
                    'session_data_sample' => array_slice($sessionData, 0, 5, true), // First 5 items for debugging
                ]);

                // Check if session has any auth-related data
                $authKeys = array_filter(array_keys($sessionData), function($key) {
                    return strpos($key, 'login_') === 0 || strpos($key, 'password_hash') === 0;
                });
                if (!empty($authKeys)) {
                    Log::warning('Session has auth keys but user() returned null', [
                        'auth_keys_found' => $authKeys,
                        'session_id' => $sessionId,
                    ]);
                }
            }
            return response()->json([
                'logged_in' => false,
                'message' => 'Unauthenticated.'
            ], 401);
        }

        // For session-based auth (Sanctum SPA mode), we don't need to check for token
        // The session authentication is sufficient
        // Only check token if it exists (for Bearer token auth fallback)
        if (method_exists($admin, 'currentAccessToken')) {
            $token = $admin->currentAccessToken();
            // If using session auth, token might be null - that's OK
            // Only fail if we're using token auth and token is missing
            if ($request->bearerToken() && !$token) {
                // Bearer token was provided but is invalid
                return response()->json([
                    'logged_in' => false,
                    'message' => 'Session expired or revoked.'
                ], 401);
            }
        }

        // ADMIN EXISTS + TOKEN VALID = LOGGED IN (200)
        return response()->json([
            'logged_in' => true,
            'admin' => [
                'id' => $admin->id,
                'name' => $admin->name,
                'email' => $admin->email,
                'avatar' => $admin->avatar ?? null,
            ],
        ], 200);
    }

    /**
     * Get authenticated admin's profile.
     */
    public function profile(Request $request)
    {

        $admin = $request->user();

        if (!$admin) {
            Log::error('Admin profile: User not authenticated');
            return response()->json([
                'status' => false,
                'message' => 'Unauthenticated.'
            ], 401);
        }

        return response()->json([
            'status' => true,
            'data' => $admin,
        ]);
    }

    /**
     * Update authenticated admin's profile (name, email, password, avatar).
     * Returns a fresh personal access token so the frontend can re-authenticate
     * after changing credentials.
     */
    public function update(Request $request)
    {
        $admin = $request->user();

        Log::debug('AdminController::update called', [
            'authenticated_user' => $admin ? get_class($admin) . ' #' . $admin->id : 'null',
            'auth_user' => auth()->user() ? get_class(auth()->user()) : 'null',
            'auth_sanctum' => auth('sanctum')->user() ? get_class(auth('sanctum')->user()) : 'null',
        ]);

        if (! $admin) {
            Log::error('Admin update: User not authenticated', [
                'request_headers' => $request->headers->all(),
            ]);
            return response()->json([
                'status' => false,
                'message' => 'Unauthenticated.'
            ], 401);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            // password is optional; if provided, minimum length enforced
            'password' => 'nullable|string|min:6',
            // avatar is optional and should be a valid URL to avoid invalid data
            'avatar' => 'nullable|url|max:2048'
        ]);

        $admin->name = $request->input('name', $admin->name);
        $admin->email = $request->input('email', $admin->email);

        if ($request->filled('avatar')) {
            $admin->avatar = $request->input('avatar');
        }

        $passwordChanged = false;
        if ($request->filled('password')) {
            $admin->password = bcrypt($request->input('password'));
            $passwordChanged = true;
        }

        try {
            $admin->save();

            // If password changed, revoke all existing tokens for security
            if ($passwordChanged && method_exists($admin, 'tokens')) {
                // tokens() is provided by Laravel\Sanctum\HasApiTokens
                $admin->tokens()->delete();
            }

            // Issue a fresh token for the frontend to use (so it can "login"
            // using the updated credentials without forcing an additional call).
            $token = $admin->createToken('api-token')->plainTextToken;

            // Persist token for debugging/inspection (optional)
            try {
                $admin->api_token = $token;
                $admin->save();
            } catch (\Exception $e) {
                // ignore failures to persist token
                Log::warning('Failed to persist admin api_token: ' . $e->getMessage());
            }

            return response()->json([
                'status' => true,
                'message' => 'Admin profile updated successfully.',
                'data' => $admin,
                'token' => $token,
            ]);
        } catch (\Exception $e) {
            // Log exception with context so frontend can return a helpful message
            Log::error('Admin update failed', [
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'admin_id' => $admin->id ?? null,
                'payload' => $request->all(),
            ]);

            return response()->json([
                'status' => false,
                'message' => 'Server error while updating profile.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}

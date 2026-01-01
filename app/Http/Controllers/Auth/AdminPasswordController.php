<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AdminPasswordController extends Controller
{
    // POST /api/admin/forgot-password
    public function forgot(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $admin = Admin::where('email', $request->email)->first();
        if (!$admin) {
            return response()->json(['message' => 'Email not found'], 404);
        }

        // generate token
        $token = Str::random(64);

        // store token in password_resets table (Laravel default)
        DB::table('password_resets')->updateOrInsert(
            ['email' => $request->email],
            ['token' => $token, 'created_at' => Carbon::now()]
        );

        // For now, return the token in response for development/testing.
        // In production you'd email the reset link to the user.
        $resetUrl = url('/reset-password') . '?token=' . $token . '&email=' . urlencode($request->email);

        return response()->json([
            'success' => true,
            'message' => 'Reset token generated',
            'token' => $token,
            'reset_url' => $resetUrl,
        ]);
    }
}

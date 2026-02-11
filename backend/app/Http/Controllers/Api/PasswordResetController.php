<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\PasswordResetMail;
use App\Models\SentEmail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class PasswordResetController extends Controller
{
    /**
     * POST /api/auth/forgot-password
     * Send password reset link to email
     */
    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'No account found with that email address.'
            ], 404);
        }

        // Generate secure random token
        $token = Str::random(64);

        // Store hashed token in database
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            [
                'email' => $request->email,
                'token' => Hash::make($token),
                'created_at' => now()
            ]
        );

        // Send password reset email
        try {
            Mail::to($request->email)->send(new PasswordResetMail($user->name, $request->email, $token));

            // Log email to database
            SentEmail::create([
                'type' => 'password_reset',
                'recipient_email' => $request->email,
                'recipient_name' => $user->name,
                'subject' => 'Password Reset Request - Quickdraw Pressing Co.',
                'user_id' => $user->id,
                'status' => 'sent',
                'sent_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Password reset link sent to your email.'
            ]);
        } catch (\Exception $e) {
            // Log failed email
            SentEmail::create([
                'type' => 'password_reset',
                'recipient_email' => $request->email,
                'recipient_name' => $user->name,
                'subject' => 'Password Reset Request - Quickdraw Pressing Co.',
                'user_id' => $user->id,
                'status' => 'failed',
                'error_message' => $e->getMessage(),
                'sent_at' => now(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to send reset email. Please try again.'
            ], 500);
        }
    }

    /**
     * POST /api/auth/reset-password
     * Reset password with token
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'token' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Find password reset record
        $resetRecord = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$resetRecord) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired reset token.'
            ], 400);
        }

        // Check if token matches (hashed comparison)
        if (!Hash::check($request->token, $resetRecord->token)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid reset token.'
            ], 400);
        }

        // Check if token is expired (60 minutes)
        if (now()->diffInMinutes($resetRecord->created_at) > 60) {
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();

            return response()->json([
                'success' => false,
                'message' => 'Reset token has expired. Please request a new one.'
            ], 400);
        }

        // Update user password
        $user = User::where('email', $request->email)->first();
        $user->update([
            'password' => Hash::make($request->password)
        ]);

        // Delete used token
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        // Revoke all existing tokens (force re-login)
        $user->tokens()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Password reset successfully. Please login with your new password.'
        ]);
    }
}

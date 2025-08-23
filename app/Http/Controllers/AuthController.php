<?php

namespace App\Http\Controllers;

use App\Models\RefreshToken;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    /**
     * TTL access token di .env => JWT_TTL=15 (menit)
     * Refresh token berlaku 30 hari (ubah kalau perlu)
     */
    protected int $refreshDays = 30;

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (! $token = JWTAuth::attempt($credentials)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();

        // ---------- REFRESH TOKEN (plain -> cookie, hash -> DB) ----------
        $refreshPlain = Str::random(64);
        $refreshHash  = hash('sha256', $refreshPlain);

        // optional: revoke semua refresh token lama user ini
        RefreshToken::where('user_id', $user->id)->update(['revoked' => true]);

        RefreshToken::create([
            'user_id'    => $user->id,
            'token_hash' => $refreshHash,
            'ip'         => $request->ip(),
            'ua'         => $request->userAgent(),
            'expires_at' => now()->addDays($this->refreshDays),
            'revoked'    => false,
        ]);

        // httpOnly cookie; set $secure=true jika sudah HTTPS
        $cookie = cookie(
            'rt',                                 // name
            $refreshPlain,                        // value (plain)
            60 * 24 * $this->refreshDays,        // minutes
            '/',                                  // path
            null,                                 // domain
            false,                                // secure (true kalau HTTPS)
            true,                                 // httpOnly
            false,                                // raw
            'Lax'                                 // sameSite
        );

        return response()->json([
            'message' => 'Login success',
            'token'   => $token,  // JWT access token
            'user'    => $user,
        ])->cookie($cookie);
    }

    public function refresh(Request $request)
    {
        // Ambil refresh token (cookie 'rt' atau fallback dari body)
        $incomingPlain = $request->cookie('rt', $request->input('refresh_token'));
        if (! $incomingPlain) {
            return response()->json(['message' => 'Missing refresh token'], 401);
        }

        $hash = hash('sha256', $incomingPlain);

        $stored = RefreshToken::where('token_hash', $hash)->first();
        if (! $stored || $stored->revoked || $stored->expires_at->isPast()) {
            return response()->json(['message' => 'Invalid refresh token'], 401);
        }

        // Issue JWT baru untuk user terkait
        $user = User::find($stored->user_id);
        if (! $user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $newJwt = JWTAuth::fromUser($user);

        // ROTATE refresh token: revoke lama, buat baru
        $stored->update(['revoked' => true]);

        $newPlain = Str::random(64);
        $newHash  = hash('sha256', $newPlain);

        RefreshToken::create([
            'user_id'    => $user->id,
            'token_hash' => $newHash,
            'ip'         => $request->ip(),
            'ua'         => $request->userAgent(),
            'expires_at' => now()->addDays($this->refreshDays),
            'revoked'    => false,
        ]);

        $cookie = cookie(
            'rt',
            $newPlain,
            60 * 24 * $this->refreshDays,
            '/',
            null,
            false, // true jika HTTPS
            true,
            false,
            'Lax'
        );

        return response()->json([
            'token' => $newJwt,
        ])->cookie($cookie);
    }

    public function logout(Request $request)
    {
        // invalidate access token JWT (kalau ada)
        try {
            if (JWTAuth::getToken()) {
                JWTAuth::invalidate(JWTAuth::getToken());
            }
        } catch (\Throwable $e) {
            // boleh diabaikan
        }

        // revoke refresh token by hash (dari cookie/body)
        $incomingPlain = $request->cookie('rt', $request->input('refresh_token'));
        if ($incomingPlain) {
            $hash = hash('sha256', $incomingPlain);
            RefreshToken::where('token_hash', $hash)->update(['revoked' => true]);
        }

        // hapus cookie
        $forget = Cookie::forget('rt');

        return response()->json(['message' => 'Logged out'])->withCookie($forget);
    }
}

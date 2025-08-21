<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    /**
     * Cek apakah user memiliki salah satu role yang diizinkan.
     * Contoh pemakaian: ->middleware('role:admin') atau ->middleware('role:admin,viewer')
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // Pastikan sudah login (middleware ini dipasang setelah auth:sanctum)
        $user = $request->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        // Normalisasi parameter role: dukung "role:admin,viewer" (CSV) maupun multi-arg
        // Laravel biasanya sudah memecah dengan multi-arg, tapi kita buat aman jika ada spasi/CSV.
        $normalized = [];
        foreach ($roles as $r) {
            foreach (explode(',', $r) as $piece) {
                $piece = trim($piece);
                if ($piece !== '') {
                    $normalized[] = $piece;
                }
            }
        }

        // Jika daftar role kosong, izinkan by default (opsional—bisa juga di-set jadi blokir)
        if (empty($normalized)) {
            return $next($request);
        }

        // Cek role user
        if (!in_array($user->role, $normalized, true)) {
            return response()->json(['message' => 'Forbidden (insufficient role)'], 403);
        }

        return $next($request);
    }
}

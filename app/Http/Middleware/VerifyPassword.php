<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class VerifyPassword
{
    /**
     * Meminta re-verifikasi password untuk aksi krusial.
     * Sesi valid selama 30 menit setelah verifikasi.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $lastVerified = session('password_verified_at');

        // Jika belum pernah verifikasi atau sudah lebih dari 30 menit
        if (!$lastVerified || now()->diffInMinutes($lastVerified) >= 30) {
            // Simpan intended URL untuk redirect setelah verifikasi
            session(['url.intended' => $request->url()]);
            return redirect()->route('password.confirm');
        }

        return $next($request);
    }
}

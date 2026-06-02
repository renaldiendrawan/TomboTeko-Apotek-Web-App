<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     * * $roles bisa berisi lebih dari 1 role yang dipisahkan koma, contoh: 'admin,apoteker'
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Pastikan user sudah login
        if (!auth()->check()) {
            return redirect('login');
        }

        // Cek apakah role user saat ini ada di dalam array $roles yang diizinkan
        if (in_array(auth()->user()->role, $roles)) {
            return $next($request);
        }

        // Jika tidak punya akses, redirect ke Landing Page ( / ) BUKAN ke /dashboard
        return redirect('/')->with('error', 'Anda tidak memiliki hak akses ke halaman tersebut.');
    }
}
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // Pastikan user sudah login & role-nya terdaftar di rute tersebut
        if (Auth::check() && in_array($request->user()->role, $roles)) {
            return $next($request);
        }

        // Jika tidak punya hak akses, lempar ke dashboard dengan pesan error
        return redirect('/dashboard')->with('error', 'Anda tidak memiliki hak akses ke halaman tersebut.');
    }
}

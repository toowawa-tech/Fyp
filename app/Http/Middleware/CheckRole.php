<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        $userRole = strtolower(Auth::user()->role);

        // Convert allowed roles parameter to lowercase
        $roles = array_map('strtolower', $roles);

        if (!in_array($userRole, $roles)) {
            abort(403, 'AKSES DITOLAK: ANDA TIDAK MEMPUNYAI KEBENARAN UNTUK MENGAKSES HALAMAN INI.');
        }

        return $next($request);
    }
}
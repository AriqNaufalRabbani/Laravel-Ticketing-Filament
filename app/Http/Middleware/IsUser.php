<?php

namespace App\Http\Middleware;

use Closure;

class IsUser
{
    public function handle($request, Closure $next)
    {
        if (auth()->check() && auth()->user()->role === 'user') {
            return $next($request);
        }

        abort(403, 'Akses ditolak: Anda bukan user.');
    }
}

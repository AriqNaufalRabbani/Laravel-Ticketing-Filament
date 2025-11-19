<?php

namespace App\Http\Middleware;

use Closure;

class IsAgent
{
    public function handle($request, Closure $next)
    {
        if (auth()->check() && auth()->user()->role === 'agent') {
            return $next($request);
        }

        abort(403, 'Akses ditolak: Anda bukan agent.');
    }
}

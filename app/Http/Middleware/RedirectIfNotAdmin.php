<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfNotAdmin
{
    public function handle($request, Closure $next)
    {
        if (!Auth::check() || !Auth::user()->hasRole('Admin')) {
            return redirect()->route('home')->with('error', 'Anda tidak memiliki akses ke admin panel.');
        }

        return $next($request);
    }
}

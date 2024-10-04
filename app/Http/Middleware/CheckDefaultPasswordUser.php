<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use Auth;

class CheckDefaultPasswordUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->default_password == true) {
            return redirect()->route('viewPassword')->with('error', 'Silakan ganti password anda agar dapat mengakses halaman lain');
        }

        return $next($request);
    }
}
